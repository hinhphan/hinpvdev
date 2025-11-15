<?php

namespace App\Services;

/**
 * Class chứa template prompt cho AI Form Data Generator.
 * Bạn có thể chỉnh sửa các method này để tùy chỉnh prompt theo nhu cầu.
 */
class AIFormDataGeneratorPrompt
{
    /**
     * Tên ngôn ngữ theo locale.
     */
    private const LOCALE_NAMES = [
        'en' => 'tiếng Anh',
        'vi' => 'tiếng Việt',
        'ja' => 'tiếng Nhật',
        'fr' => 'tiếng Pháp',
        'de' => 'tiếng Đức',
        'es' => 'tiếng Tây Ban Nha',
        'it' => 'tiếng Ý',
        'pt' => 'tiếng Bồ Đào Nha',
        'ru' => 'tiếng Nga',
        'ar' => 'tiếng Ả Rập',
    ];

    /**
     * Build system message (phần ít thay đổi - sẽ được cache).
     * Chứa yêu cầu chung, format JSON, và các quy tắc.
     *
     * @return string
     */
    public static function getSystemMessage(): string
    {
        return "Bạn là chuyên gia tạo dữ liệu test cho form. Trả về JSON {\"test_data\": {...}}.\n\n" .
               "Yêu cầu:\n" .
               "- Tuân thủ min/max/min_length/max_length\n" .
               "- email: hợp lệ, chỉ dùng chữ Latin (a-z, A-Z, 0-9, @, ., -, _)\n" .
               "- text/textarea/search: đơn giản, không cần câu hoàn chỉnh\n" .
               "- select/radio: chọn từ options\n" .
               "- password: đúng độ dài | number: min-max | date: YYYY-MM-DD\n" .
               "- file: {\"name\": \"...\", \"size\": ..., \"type\": \"...\"} | checkbox: true/false";
    }

    /**
     * Build user message (phần thay đổi theo request - không cache).
     * Chứa fields cụ thể với label, placeholder, constraints, options.
     *
     * @param array $fields Form fields configuration
     * @param string|null $locale Locale
     * @return string
     */
    public static function buildUserMessage(array $fields, ?string $locale = 'en'): string
    {
        $localeName = self::LOCALE_NAMES[$locale] ?? 'tiếng Anh';
        
        // Đưa cấu trúc JSON lên đầu để model parse nhanh hơn
        $fieldNames = array_map(function($field) {
            return $field['name'] ?? 'field';
        }, $fields);
        
        // Tạo JSON structure với tất cả field names
        $jsonFields = [];
        foreach ($fieldNames as $name) {
            $jsonFields[] = "\"{$name}\": \"...\"";
        }
        $jsonStructure = "{\"test_data\": {" . implode(", ", $jsonFields) . "}}";
        
        $message = "Tạo dữ liệu test cho form. Trả về JSON:\n{$jsonStructure}\n\n";
        $message .= "Ngôn ngữ: {$localeName}\n\n";
        
        // Liệt kê fields với đầy đủ context (label, placeholder, constraints, options)
        $message .= "Fields:\n";
        foreach ($fields as $field) {
            $name = $field['name'] ?? 'field';
            $type = strtolower($field['type'] ?? 'text');
            
            $message .= "- {$name} (type:{$type}";
            
            // Thêm label nếu có (quan trọng để AI hiểu context)
            if (isset($field['label']) && !empty($field['label'])) {
                $message .= ", label:\"{$field['label']}\"";
            }
            
            // Thêm placeholder nếu có (giúp AI hiểu format mong muốn)
            if (isset($field['placeholder']) && !empty($field['placeholder'])) {
                $message .= ", placeholder:\"{$field['placeholder']}\"";
            }
            
            // Thêm constraints nếu có
            $constraints = [];
            if (isset($field['min'])) $constraints[] = "min:{$field['min']}";
            if (isset($field['max'])) $constraints[] = "max:{$field['max']}";
            if (isset($field['min_length'])) $constraints[] = "min_len:{$field['min_length']}";
            if (isset($field['max_length'])) $constraints[] = "max_len:{$field['max_length']}";
            
            if (!empty($constraints)) {
                $message .= ", " . implode(", ", $constraints);
            }
            
            // Options cho select/radio (hiển thị đầy đủ để AI hiểu context)
            if (isset($field['options']) && is_array($field['options']) && !empty($field['options'])) {
                $validOptions = array_filter($field['options'], fn($opt) => $opt !== null);
                if (!empty($validOptions)) {
                    // Hiển thị tất cả options (không giới hạn) để AI hiểu đầy đủ context
                    $optionsList = implode("|", array_values($validOptions));
                    $message .= ", options:{$optionsList}";
                }
            }
            
            $message .= ")\n";
        }
        
        return $message;
    }

    /**
     * Build prompt (backward compatibility - gộp system + user).
     * 
     * @deprecated Sử dụng getSystemMessage() và buildUserMessage() để tận dụng cache
     * @param array $fields Form fields configuration
     * @param string|null $locale Locale
     * @return string
     */
    public static function buildPrompt(array $fields, ?string $locale = 'en'): string
    {
        return self::getSystemMessage() . "\n\n" . self::buildUserMessage($fields, $locale);
    }

}


