<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AIFormDataGenerator
{
    private ?string $apiKey = null;
    private Client $httpClient;
    private string $model = 'gpt-5-nano';
    private int $timeout = 60; // Timeout in seconds
    private string $baseUrl = 'https://api.openai.com';

    /**
     * Initialize HTTP client for OpenAI API.
     */
    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY');
        
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'connect_timeout' => 10,
            'http_errors' => true,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->apiKey ? "Bearer {$this->apiKey}" : '',
            ],
        ]);
    }

    /**
     * Build prompt using the prompt template class.
     * Bạn có thể chỉnh sửa AIFormDataGeneratorPrompt để tùy chỉnh prompt.
     *
     * @param array $fields
     * @param string|null $locale
     * @return string
     */
    private function buildPrompt(array $fields, ?string $locale = 'en'): string
    {
        return AIFormDataGeneratorPrompt::buildPrompt($fields, $locale);
    }

    /**
     * Generate test data using OpenAI API.
     *
     * @param array $fields Form fields configuration
     * @param string|null $locale Locale for data generation
     * @return array Generated test data
     */
    public function generate(array $fields, ?string $locale = 'en'): array
    {
        if (!$this->apiKey) {
            throw new \RuntimeException('OpenAI API key chưa được cấu hình. Vui lòng thêm OPENAI_API_KEY vào file .env');
        }

        $startTime = microtime(true);
        
        // Tách system message (cache) và user message (thay đổi)
        $systemMessage = AIFormDataGeneratorPrompt::getSystemMessage();
        $userMessage = AIFormDataGeneratorPrompt::buildUserMessage($fields, $locale);
        
        // Kiểm tra và log trùng lặp field names
        $fieldNames = array_map(fn($f) => $f['name'] ?? 'unknown', $fields);
        $duplicateFields = array_filter(array_count_values($fieldNames), fn($count) => $count > 1);
        
        if (!empty($duplicateFields)) {
            Log::warning('Duplicate field names detected', [
                'duplicates' => $duplicateFields,
                'field_counts' => array_count_values($fieldNames),
            ]);
        }
        
        $totalPromptLength = strlen($systemMessage) + strlen($userMessage);
        
        Log::info('OpenAI API Request Started', [
            'endpoint' => 'v1/chat/completions',
            'model' => $this->model,
            'locale' => $locale,
            'fields_count' => count($fields),
            'system_message_length' => strlen($systemMessage),
            'user_message_length' => strlen($userMessage),
            'total_prompt_length' => $totalPromptLength,
            'note' => 'Sử dụng chat/completions với tối ưu tốc độ (temperature=0.3, max_tokens=2000)',
        ]);
        
        Log::info('OpenAI API Prompt Content', [
            'system_message' => $systemMessage,
            'user_message' => $userMessage,
        ]);
        
        try {
            $requestStartTime = microtime(true);
            
            // Responses API yêu cầu prompt.id - có thể cần tạo prompt trước hoặc format khác
            // Tạm thời quay lại chat/completions với format tối ưu cho tốc độ
            // Hoặc có thể Responses API cần workflow khác (tạo prompt trước, sau đó dùng ID)
            
            // Thử format đơn giản hơn: dùng chat/completions nhưng tối ưu
            $endpoint = 'v1/chat/completions';
            
            // Gộp system và user message thành một prompt ngắn gọn
            $fullPrompt = $systemMessage . "\n\n" . $userMessage;
            
            // Sử dụng chat/completions với format tối ưu
            // Model gpt-5-nano có các giới hạn: chỉ hỗ trợ temperature default (1), dùng max_completion_tokens
            $requestBody = [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $fullPrompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
            ];
            
            // Model gpt-5-nano: dùng max_completion_tokens, không hỗ trợ temperature tùy chỉnh
            if (strpos($this->model, 'gpt-5') !== false) {
                $requestBody['max_completion_tokens'] = 2000;
                // Không set temperature - model chỉ hỗ trợ default (1)
            } else {
                // Model cũ: dùng max_tokens và có thể tùy chỉnh temperature
                $requestBody['max_tokens'] = 2000;
                $requestBody['temperature'] = 0.3; // Tối ưu tốc độ cho model cũ
            }
            
            $response = $this->httpClient->post($endpoint, [
                'json' => $requestBody,
            ]);

            $requestTime = microtime(true) - $requestStartTime;
            
            $responseBody = json_decode($response->getBody()->getContents(), true);
            
            if (!$responseBody) {
                throw new \RuntimeException('Không thể parse response từ OpenAI API');
            }
            
            // Log response metadata
            $usage = $responseBody['usage'] ?? null;
            $responseId = $responseBody['id'] ?? null;
            $model = $responseBody['model'] ?? $this->model;
            
            Log::info('OpenAI API Request Completed', [
                'endpoint' => $endpoint,
                'request_time_seconds' => round($requestTime, 3),
                'response_id' => $responseId,
                'model_used' => $model,
                'prompt_tokens' => $usage['prompt_tokens'] ?? 'N/A',
                'completion_tokens' => $usage['completion_tokens'] ?? 'N/A',
                'total_tokens' => $usage['total_tokens'] ?? 'N/A',
            ]);

            // Chat/completions response format
            $content = null;
            
            // Format chat/completions: response.choices[0].message.content
            if (isset($responseBody['choices'][0]['message']['content'])) {
                $content = $responseBody['choices'][0]['message']['content'];
            } elseif (isset($responseBody['text'])) {
                // Fallback cho responses API format (nếu có)
                $content = $responseBody['text'];
            } elseif (isset($responseBody['content'])) {
                // Fallback khác
                $content = $responseBody['content'];
            } elseif (isset($responseBody['choices'][0]['text'])) {
                // Fallback cho completions format cũ
                $content = $responseBody['choices'][0]['text'];
            }
            
            if (!$content) {
                Log::warning('OpenAI API returned empty content', [
                    'response_id' => $responseId,
                    'response_structure' => array_keys($responseBody),
                ]);
                throw new \RuntimeException('Không nhận được phản hồi từ OpenAI API');
            }

            $parseStartTime = microtime(true);
            $data = json_decode($content, true);
            $parseTime = microtime(true) - $parseStartTime;
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OpenAI response không phải JSON hợp lệ', [
                    'response_id' => $responseId,
                    'response_length' => strlen($content),
                    'response_preview' => substr($content, 0, 200),
                    'error' => json_last_error_msg(),
                    'parse_time_seconds' => round($parseTime, 3),
                ]);
                throw new \RuntimeException('Phản hồi từ OpenAI không phải JSON hợp lệ');
            }

            $totalTime = microtime(true) - $startTime;
            
            Log::info('OpenAI API Data Generated Successfully', [
                'total_time_seconds' => round($totalTime, 3),
                'request_time_seconds' => round($requestTime, 3),
                'parse_time_seconds' => round($parseTime, 3),
                'fields_generated' => count($data['test_data'] ?? $data),
                'response_size_bytes' => strlen($content),
            ]);

            return $data['test_data'] ?? $data;

        } catch (RequestException $e) {
            $totalTime = microtime(true) - $startTime;
            
            $errorMessage = $e->getMessage();
            $statusCode = 0;
            $errorBody = null;
            
            // Parse error response nếu có
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                
                try {
                    $errorBody = json_decode($response->getBody()->getContents(), true);
                } catch (\Exception $parseError) {
                    // Ignore parse error
                }
            }
            
            Log::error('OpenAI API HTTP Exception', [
                'error_type' => 'RequestException',
                'error_message' => $errorMessage,
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'total_time_seconds' => round($totalTime, 3),
                'fields_count' => count($fields),
                'locale' => $locale,
            ]);
            
            throw new \RuntimeException(
                $errorBody['error']['message'] ?? $errorMessage,
                $statusCode ?: $e->getCode()
            );
            
        } catch (GuzzleException $e) {
            $totalTime = microtime(true) - $startTime;
            
            Log::error('OpenAI API Guzzle Exception', [
                'error_type' => 'GuzzleException',
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'total_time_seconds' => round($totalTime, 3),
                'fields_count' => count($fields),
                'locale' => $locale,
            ]);
            
            throw new \RuntimeException($e->getMessage(), $e->getCode());
            
        } catch (\Exception $e) {
            $totalTime = microtime(true) - $startTime;
            
            Log::error('OpenAI API General Exception', [
                'error_type' => get_class($e),
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'total_time_seconds' => round($totalTime, 3),
                'fields_count' => count($fields),
                'locale' => $locale,
            ]);
            throw $e;
        }
    }

    /**
     * Set OpenAI model.
     *
     * @param string $model
     * @return self
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Set timeout for HTTP requests.
     *
     * @param int $timeout Timeout in seconds
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'connect_timeout' => 10,
            'http_errors' => true,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->apiKey ? "Bearer {$this->apiKey}" : '',
            ],
        ]);
        return $this;
    }

    /**
     * Check if OpenAI client is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->apiKey !== null;
    }
}

