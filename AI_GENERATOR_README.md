# AI Form Data Generator

## Tổng quan

Hệ thống hỗ trợ tạo dữ liệu test cho form bằng AI (OpenAI) hoặc Faker. Bạn có thể chọn generator thông qua parameter `generator` trong request.

## Cài đặt

### 1. Cài đặt package

Package `openai-php/client` đã được cài đặt. Nếu chưa có, chạy:

```bash
composer require openai-php/client
```

### 2. Cấu hình API Key

Thêm vào file `.env`:

```env
OPENAI_API_KEY=sk-your-api-key-here
```

API key sẽ được đọc từ `config/services.php` hoặc trực tiếp từ `.env`.

## Sử dụng

### Request với Faker (mặc định)

```json
{
  "locale": "en",
  "fields": [
    {
      "name": "email",
      "type": "email",
      "label": "Email Address"
    }
  ]
}
```

### Request với AI Generator

```json
{
  "locale": "en",
  "generator": "ai",
  "fields": [
    {
      "name": "email",
      "type": "email",
      "label": "Email Address"
    }
  ]
}
```

### Parameters

- `generator`: `"faker"` (mặc định) hoặc `"ai"`
- `locale`: Ngôn ngữ cho dữ liệu (en, ja, fr, de, es, it, pt, ru, ar)
- `fields`: Mảng các trường form

## Tùy chỉnh Prompt

### File: `app/Services/AIFormDataGeneratorPrompt.php`

File này chứa template prompt bằng tiếng Việt. Bạn có thể chỉnh sửa các method sau để tùy chỉnh:

1. **`getSystemMessage()`**: System message cho OpenAI
2. **`buildPrompt()`**: Prompt chính cho user
3. **`getRequirementsSection()`**: Phần yêu cầu về dữ liệu
4. **`getFormatSection()`**: Phần định dạng kết quả JSON

### Ví dụ chỉnh sửa prompt

```php
// Thay đổi system message
public static function getSystemMessage(): string
{
    return 'Bạn là một chuyên gia tạo dữ liệu test cho form. Tạo dữ liệu thực tế và đa dạng.';
}

// Thêm yêu cầu mới vào requirements
private static function getRequirementsSection(string $localeName): string
{
    $section = "3. Yêu cầu về dữ liệu:\n";
    $section .= "   - Dữ liệu phải thực tế và hợp lý\n";
    $section .= "   - [Thêm yêu cầu của bạn ở đây]\n";
    // ...
    return $section;
}
```

## Cấu trúc Prompt hiện tại

Prompt được xây dựng với các phần:

1. **Ngôn ngữ**: Xác định ngôn ngữ cho dữ liệu
2. **Các trường form**: Liệt kê từng trường với:
   - Tên trường
   - Loại (type)
   - Nhãn (label) - nếu có
   - Constraints (min, max, min_length, max_length)
   - Options (cho select/radio)
   - Placeholder - nếu có
3. **Yêu cầu về dữ liệu**: Các quy tắc tạo dữ liệu
4. **Định dạng kết quả**: Cấu trúc JSON mong đợi

## Tùy chỉnh AI Generator

### Thay đổi Model

Trong `app/Services/AIFormDataGenerator.php`:

```php
$aiGenerator = new AIFormDataGenerator();
$aiGenerator->setModel('gpt-4'); // Thay đổi model
$testData = $aiGenerator->generate($fields, $locale);
```

### Thay đổi Temperature

```php
$aiGenerator->setTemperature(0.9); // Tăng tính ngẫu nhiên
```

## Error Handling

Nếu AI generator gặp lỗi (API key không hợp lệ, network error, etc.), hệ thống sẽ tự động fallback về Faker và trả về response với field `ai_error` chứa thông tin lỗi.

## Test Cases

Xem file `auto-forms-api.http`:
- Test 30: AI Generator - Basic Form
- Test 31: AI Generator - Japanese Locale
- Test 32: AI Generator - Complex Form

## Lưu ý

1. **API Key**: Cần có OpenAI API key hợp lệ để sử dụng AI generator
2. **Cost**: Mỗi request sẽ tốn phí API của OpenAI
3. **Rate Limits**: Chú ý rate limits của OpenAI API
4. **Fallback**: Hệ thống tự động fallback về Faker nếu AI fail

## Troubleshooting

### Lỗi: "OpenAI API key chưa được cấu hình"

- Kiểm tra file `.env` có `OPENAI_API_KEY`
- Kiểm tra `config/services.php` có cấu hình `openai.api_key`

### Lỗi: "Phản hồi từ OpenAI không phải JSON hợp lệ"

- Kiểm tra logs trong `storage/logs/laravel.log`
- Có thể do model không hỗ trợ `response_format: json_object`
- Thử đổi model hoặc kiểm tra prompt

### Lỗi: Timeout

- Tăng timeout trong HTTP client (xem documentation của openai-php/client)
- Hoặc sử dụng model nhanh hơn (gpt-4o-mini thay vì gpt-4)

