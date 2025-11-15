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
        $prompt = $this->buildPrompt($fields, $locale);
        $systemMessage = AIFormDataGeneratorPrompt::getSystemMessage();
        $fullPrompt = $systemMessage . "\n\n" . $prompt;
        
        // Kiểm tra và log trùng lặp field names
        $fieldNames = array_map(fn($f) => $f['name'] ?? 'unknown', $fields);
        $duplicateFields = array_filter(array_count_values($fieldNames), fn($count) => $count > 1);
        
        if (!empty($duplicateFields)) {
            Log::warning('Duplicate field names detected', [
                'duplicates' => $duplicateFields,
                'field_counts' => array_count_values($fieldNames),
            ]);
        }
        
        Log::info('OpenAI API Request Started', [
            'model' => $this->model,
            'locale' => $locale,
            'fields_count' => count($fields),
            'prompt_length' => strlen($fullPrompt),
        ]);
        
        Log::info('OpenAI API Prompt Content', ['full_prompt' => $fullPrompt]);
        
        try {
            $requestStartTime = microtime(true);
            
            // Gọi trực tiếp OpenAI API theo documentation: https://platform.openai.com/docs/guides/text
            $response = $this->httpClient->post('v1/chat/completions', [
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => AIFormDataGeneratorPrompt::getSystemMessage()
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                ],
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
                'request_time_seconds' => round($requestTime, 3),
                'response_id' => $responseId,
                'model_used' => $model,
                'prompt_tokens' => $usage['prompt_tokens'] ?? 'N/A',
                'completion_tokens' => $usage['completion_tokens'] ?? 'N/A',
                'total_tokens' => $usage['total_tokens'] ?? 'N/A',
            ]);

            $content = $responseBody['choices'][0]['message']['content'] ?? null;
            
            if (!$content) {
                Log::warning('OpenAI API returned empty content', [
                    'response_id' => $responseId,
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

