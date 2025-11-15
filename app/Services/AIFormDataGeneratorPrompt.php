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
     * Build system message cho OpenAI.
     * Bạn có thể chỉnh sửa message này để thay đổi hành vi của AI.
     *
     * @return string
     */
    public static function getSystemMessage(): string
    {
        return 'Bạn là một chuyên gia tạo dữ liệu test cho form. Bạn sẽ nhận thông tin về các trường form và tạo dữ liệu test phù hợp. Luôn trả về kết quả dưới dạng JSON hợp lệ.';
    }

    /**
     * Build user prompt cho OpenAI.
     * Đây là prompt chính mà bạn có thể chỉnh sửa để thay đổi cách AI tạo dữ liệu.
     *
     * @param array $fields Form fields configuration
     * @param string|null $locale Locale
     * @return string
     */
    public static function buildPrompt(array $fields, ?string $locale = 'en'): string
    {
        $localeName = self::LOCALE_NAMES[$locale] ?? 'tiếng Anh';
        
        $prompt = "Hãy tạo dữ liệu test cho form với các yêu cầu sau:\n\n";
        $prompt .= "1. Ngôn ngữ: Tất cả dữ liệu phải được tạo bằng {$localeName}\n\n";
        $prompt .= "2. Các trường form cần tạo dữ liệu:\n\n";

        foreach ($fields as $index => $field) {
            $fieldNum = $index + 1;
            $name = $field['name'] ?? 'field_' . $fieldNum;
            $type = strtolower($field['type'] ?? 'text');
            $label = $field['label'] ?? null;
            
            $prompt .= "Trường {$fieldNum}: {$name}\n";
            $prompt .= "  - Loại: {$type}\n";
            
            if ($label) {
                $prompt .= "  - Nhãn: {$label}\n";
            }
            
            // Thêm thông tin về constraints
            if (isset($field['min'])) {
                $prompt .= "  - Giá trị tối thiểu: {$field['min']}\n";
            }
            if (isset($field['max'])) {
                $prompt .= "  - Giá trị tối đa: {$field['max']}\n";
            }
            if (isset($field['min_length'])) {
                $prompt .= "  - Độ dài tối thiểu: {$field['min_length']} ký tự\n";
            }
            if (isset($field['max_length'])) {
                $prompt .= "  - Độ dài tối đa: {$field['max_length']} ký tự\n";
            }
            
            // Thêm options cho select/radio
            if (isset($field['options']) && is_array($field['options']) && !empty($field['options'])) {
                $validOptions = array_filter($field['options'], fn($opt) => $opt !== null);
                if (!empty($validOptions)) {
                    $optionsList = implode(', ', array_values($validOptions));
                    $prompt .= "  - Các lựa chọn: {$optionsList}\n";
                }
            }
            
            // Thêm placeholder nếu có
            if (isset($field['placeholder'])) {
                $prompt .= "  - Placeholder: {$field['placeholder']}\n";
            }
            
            $prompt .= "\n";
        }

        $prompt .= self::getRequirementsSection($localeName);
        $prompt .= self::getFormatSection($fields);

        return $prompt;
    }

    /**
     * Phần yêu cầu về dữ liệu (đã tối ưu để ngắn gọn hơn).
     * Bạn có thể chỉnh sửa phần này để thay đổi các yêu cầu về dữ liệu.
     *
     * @param string $localeName
     * @return string
     */
    private static function getRequirementsSection(string $localeName): string
    {
        // Tối ưu: rút gọn yêu cầu nhưng vẫn đầy đủ
        $section = "3. Yêu cầu:\n";
        $section .= "   - Dữ liệu thực tế, tuân thủ min/max/min_length/max_length\n";
        $section .= "   - select/radio: chọn từ options đã cho\n";
        $section .= "   - email: hợp lệ, PHẢI dùng chữ cái Latin (a-z, A-Z, 0-9, @, ., -, _), KHÔNG được dùng ký tự đặc biệt như tiếng Nhật, tiếng Trung, v.v. Ví dụ: john@example.com (đúng), たろう@example.jp (sai)\n";
        $section .= "   - password: đúng độ dài | number: trong khoảng min-max\n";
        $section .= "   - date: YYYY-MM-DD | datetime: YYYY-MM-DDTHH:mm | time: HH:mm\n";
        $section .= "   - file: {name, size, type} | checkbox: true/false\n";
        $section .= "   - text/textarea/search: văn bản có ý nghĩa bằng {$localeName}\n\n";
        
        return $section;
    }

    /**
     * Phần định dạng kết quả (đã tối ưu để ngắn gọn hơn).
     * Bạn có thể chỉnh sửa phần này để thay đổi cấu trúc JSON trả về.
     *
     * @param array $fields
     * @return string
     */
    private static function getFormatSection(array $fields): string
    {
        // Tối ưu: chỉ liệt kê field names thay vì toàn bộ cấu trúc
        $fieldNames = array_map(function($field) {
            return $field['name'] ?? 'field';
        }, $fields);
        
        $fieldList = implode(', ', $fieldNames);
        
        $section = "4. Định dạng: Trả về JSON {\"test_data\": {";
        $section .= "\"{$fieldList}\"}}.\n";
        $section .= "   - File fields: {\"name\": \"...\", \"size\": ..., \"type\": \"...\"}\n";
        $section .= "   - Các field khác: giá trị phù hợp với type\n\n";
        $section .= "Tạo dữ liệu và trả về JSON.";
        
        return $section;
    }
}

