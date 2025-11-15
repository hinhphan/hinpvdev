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
     * Build prompt cho OpenAI (gộp system + user thành một để tối ưu).
     * Đưa cấu trúc JSON lên đầu để model parse nhanh hơn.
     *
     * @param array $fields Form fields configuration
     * @param string|null $locale Locale
     * @return string
     */
    public static function buildPrompt(array $fields, ?string $locale = 'en'): string
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
        
        $prompt = "Tạo dữ liệu test cho form. Trả về JSON:\n{$jsonStructure}\n\n";
        
        // Yêu cầu ngắn gọn
        $prompt .= "Ngôn ngữ: {$localeName}\n";
        $prompt .= "Yêu cầu:\n";
        $prompt .= "- Tuân thủ min/max/min_length/max_length\n";
        $prompt .= "- email: hợp lệ, chỉ dùng chữ Latin (a-z, A-Z, 0-9, @, ., -, _)\n";
        $prompt .= "- text/textarea/search: {$localeName} đơn giản, không cần câu hoàn chỉnh\n";
        $prompt .= "- select/radio: chọn từ options\n";
        $prompt .= "- password: đúng độ dài | number: min-max | date: YYYY-MM-DD\n";
        $prompt .= "- file: {\"name\": \"...\", \"size\": ..., \"type\": \"...\"} | checkbox: true/false\n\n";
        
        // Liệt kê fields với đầy đủ context (label, placeholder, constraints, options)
        $prompt .= "Fields:\n";
        foreach ($fields as $field) {
            $name = $field['name'] ?? 'field';
            $type = strtolower($field['type'] ?? 'text');
            
            $prompt .= "- {$name} (type:{$type}";
            
            // Thêm label nếu có (quan trọng để AI hiểu context)
            if (isset($field['label']) && !empty($field['label'])) {
                $prompt .= ", label:\"{$field['label']}\"";
            }
            
            // Thêm placeholder nếu có (giúp AI hiểu format mong muốn)
            if (isset($field['placeholder']) && !empty($field['placeholder'])) {
                $prompt .= ", placeholder:\"{$field['placeholder']}\"";
            }
            
            // Thêm constraints nếu có
            $constraints = [];
            if (isset($field['min'])) $constraints[] = "min:{$field['min']}";
            if (isset($field['max'])) $constraints[] = "max:{$field['max']}";
            if (isset($field['min_length'])) $constraints[] = "min_len:{$field['min_length']}";
            if (isset($field['max_length'])) $constraints[] = "max_len:{$field['max_length']}";
            
            if (!empty($constraints)) {
                $prompt .= ", " . implode(", ", $constraints);
            }
            
            // Options cho select/radio (hiển thị đầy đủ để AI hiểu context)
            if (isset($field['options']) && is_array($field['options']) && !empty($field['options'])) {
                $validOptions = array_filter($field['options'], fn($opt) => $opt !== null);
                if (!empty($validOptions)) {
                    // Hiển thị tất cả options (không giới hạn) để AI hiểu đầy đủ context
                    $optionsList = implode("|", array_values($validOptions));
                    $prompt .= ", options:{$optionsList}";
                }
            }
            
            $prompt .= ")\n";
        }
        
        return $prompt;
    }

}


