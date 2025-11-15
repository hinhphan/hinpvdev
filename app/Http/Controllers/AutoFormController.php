<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutoFormRequest;
use App\Services\AIFormDataGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AutoFormController extends Controller
{
    /**
     * Whitelist of allowed locales.
     * Maps client locale to Faker locale.
     * Only locales with full Faker support are included.
     */
    private const ALLOWED_LOCALES = [
        'en' => 'en_US', // English
        'vi' => 'vi_VN', // Vietnamese
        'ja' => 'ja_JP', // Japanese
        'fr' => 'fr_FR', // French
        'de' => 'de_DE', // German
        'es' => 'es_ES', // Spanish
        'it' => 'it_IT', // Italian
        'pt' => 'pt_BR', // Portuguese
        'ru' => 'ru_RU', // Russian
        'ar' => 'ar_SA', // Arabic
    ];

    /**
     * Locales that are known to be fully supported by Faker.
     */
    private const SUPPORTED_FAKER_LOCALES = [
        'en_US', 'vi_VN', 'ja_JP', 'fr_FR', 'de_DE', 'es_ES', 'it_IT', 'pt_BR', 'ru_RU', 'ar_SA'
    ];

    /**
     * Default locale (fallback).
     */
    private const DEFAULT_LOCALE = 'en';

    /**
     * Get list of supported field types.
     * 
     * @return JsonResponse
     */
    public function types(): JsonResponse
    {
        $types = [
            // Email types
            ['value' => 'email', 'text' => 'Email', 'group_name' => 'Email'],
            ['value' => 'email-safe', 'text' => 'Email Safe', 'group_name' => 'Email'],
            ['value' => 'email-free', 'text' => 'Email Free', 'group_name' => 'Email'],
            ['value' => 'email-company', 'text' => 'Email Company', 'group_name' => 'Email'],
            
            // Password
            ['value' => 'password', 'text' => 'Password', 'group_name' => 'Security'],
            
            // Phone
            ['value' => 'tel', 'text' => 'Tel', 'group_name' => 'Contact'],
            ['value' => 'phone', 'text' => 'Phone', 'group_name' => 'Contact'],
            ['value' => 'telephone', 'text' => 'Telephone', 'group_name' => 'Contact'],
            
            // URL & Domain
            ['value' => 'url', 'text' => 'URL', 'group_name' => 'Web'],
            ['value' => 'domain', 'text' => 'Domain', 'group_name' => 'Web'],
            ['value' => 'domain-name', 'text' => 'Domain Name', 'group_name' => 'Web'],
            ['value' => 'slug', 'text' => 'Slug', 'group_name' => 'Web'],
            
            // Numbers
            ['value' => 'number', 'text' => 'Number', 'group_name' => 'Number'],
            ['value' => 'numeric', 'text' => 'Numeric', 'group_name' => 'Number'],
            ['value' => 'random-digit', 'text' => 'Random Digit', 'group_name' => 'Number'],
            ['value' => 'random-digit-not-zero', 'text' => 'Random Digit Not Zero', 'group_name' => 'Number'],
            ['value' => 'random-number', 'text' => 'Random Number', 'group_name' => 'Number'],
            ['value' => 'random-float', 'text' => 'Random Float', 'group_name' => 'Number'],
            ['value' => 'range', 'text' => 'Range', 'group_name' => 'Number'],
            
            // Date & Time
            ['value' => 'date', 'text' => 'Date', 'group_name' => 'Date & Time'],
            ['value' => 'datetime', 'text' => 'DateTime', 'group_name' => 'Date & Time'],
            ['value' => 'datetime-local', 'text' => 'DateTime Local', 'group_name' => 'Date & Time'],
            ['value' => 'time', 'text' => 'Time', 'group_name' => 'Date & Time'],
            ['value' => 'month', 'text' => 'Month', 'group_name' => 'Date & Time'],
            ['value' => 'week', 'text' => 'Week', 'group_name' => 'Date & Time'],
            ['value' => 'unix-time', 'text' => 'Unix Time', 'group_name' => 'Date & Time'],
            ['value' => 'iso8601', 'text' => 'ISO8601', 'group_name' => 'Date & Time'],
            
            // Color
            ['value' => 'color', 'text' => 'Color', 'group_name' => 'Color'],
            ['value' => 'hex-color', 'text' => 'Hex Color', 'group_name' => 'Color'],
            ['value' => 'safe-hex-color', 'text' => 'Safe Hex Color', 'group_name' => 'Color'],
            ['value' => 'rgb-color', 'text' => 'RGB Color', 'group_name' => 'Color'],
            ['value' => 'rgb-css-color', 'text' => 'RGB CSS Color', 'group_name' => 'Color'],
            ['value' => 'rgba-css-color', 'text' => 'RGBA CSS Color', 'group_name' => 'Color'],
            ['value' => 'hsl-color', 'text' => 'HSL Color', 'group_name' => 'Color'],
            
            // Boolean
            ['value' => 'checkbox', 'text' => 'Checkbox', 'group_name' => 'Boolean'],
            ['value' => 'boolean', 'text' => 'Boolean', 'group_name' => 'Boolean'],
            
            // Select & Radio
            ['value' => 'radio', 'text' => 'Radio', 'group_name' => 'Selection'],
            ['value' => 'select', 'text' => 'Select', 'group_name' => 'Selection'],
            
            // Text types
            ['value' => 'textarea', 'text' => 'Textarea', 'group_name' => 'Text'],
            ['value' => 'search', 'text' => 'Search', 'group_name' => 'Text'],
            ['value' => 'text', 'text' => 'Text', 'group_name' => 'Text'],
            ['value' => 'input', 'text' => 'Input', 'group_name' => 'Text'],
            ['value' => 'word', 'text' => 'Word', 'group_name' => 'Text'],
            ['value' => 'words', 'text' => 'Words', 'group_name' => 'Text'],
            ['value' => 'sentence', 'text' => 'Sentence', 'group_name' => 'Text'],
            ['value' => 'paragraph', 'text' => 'Paragraph', 'group_name' => 'Text'],
            
            // File
            ['value' => 'file', 'text' => 'File', 'group_name' => 'File'],
            ['value' => 'file-upload', 'text' => 'File Upload', 'group_name' => 'File'],
            ['value' => 'mime-type', 'text' => 'MIME Type', 'group_name' => 'File'],
            ['value' => 'file-extension', 'text' => 'File Extension', 'group_name' => 'File'],
            
            // Network
            ['value' => 'ip', 'text' => 'IP', 'group_name' => 'Network'],
            ['value' => 'ipv4', 'text' => 'IPv4', 'group_name' => 'Network'],
            ['value' => 'ipv6', 'text' => 'IPv6', 'group_name' => 'Network'],
            ['value' => 'local-ipv4', 'text' => 'Local IPv4', 'group_name' => 'Network'],
            ['value' => 'mac-address', 'text' => 'MAC Address', 'group_name' => 'Network'],
            ['value' => 'user-agent', 'text' => 'User Agent', 'group_name' => 'Network'],
            
            // Payment
            ['value' => 'credit-card', 'text' => 'Credit Card', 'group_name' => 'Payment'],
            ['value' => 'creditcard', 'text' => 'Credit Card', 'group_name' => 'Payment'],
            ['value' => 'credit-card-type', 'text' => 'Credit Card Type', 'group_name' => 'Payment'],
            ['value' => 'credit-card-expiration', 'text' => 'Credit Card Expiration', 'group_name' => 'Payment'],
            ['value' => 'iban', 'text' => 'IBAN', 'group_name' => 'Payment'],
            ['value' => 'swift-bic', 'text' => 'SWIFT BIC', 'group_name' => 'Payment'],
            
            // Identifiers
            ['value' => 'hidden', 'text' => 'Hidden', 'group_name' => 'Identifier'],
            ['value' => 'uuid', 'text' => 'UUID', 'group_name' => 'Identifier'],
            ['value' => 'ean13', 'text' => 'EAN13', 'group_name' => 'Identifier'],
            ['value' => 'ean8', 'text' => 'EAN8', 'group_name' => 'Identifier'],
            ['value' => 'isbn10', 'text' => 'ISBN10', 'group_name' => 'Identifier'],
            ['value' => 'isbn13', 'text' => 'ISBN13', 'group_name' => 'Identifier'],
            ['value' => 'barcode', 'text' => 'Barcode', 'group_name' => 'Identifier'],
            
            // Hash
            ['value' => 'md5', 'text' => 'MD5', 'group_name' => 'Hash'],
            ['value' => 'sha1', 'text' => 'SHA1', 'group_name' => 'Hash'],
            ['value' => 'sha256', 'text' => 'SHA256', 'group_name' => 'Hash'],
            
            // Location
            ['value' => 'country-code', 'text' => 'Country Code', 'group_name' => 'Location'],
            ['value' => 'country-iso-alpha3', 'text' => 'Country ISO Alpha3', 'group_name' => 'Location'],
            ['value' => 'language-code', 'text' => 'Language Code', 'group_name' => 'Location'],
            ['value' => 'currency-code', 'text' => 'Currency Code', 'group_name' => 'Location'],
            ['value' => 'timezone', 'text' => 'Timezone', 'group_name' => 'Location'],
            
            // Other
            ['value' => 'emoji', 'text' => 'Emoji', 'group_name' => 'Other'],
            ['value' => 'semver', 'text' => 'Semver', 'group_name' => 'Other'],
            ['value' => 'version', 'text' => 'Version', 'group_name' => 'Other'],
            ['value' => 'username', 'text' => 'Username', 'group_name' => 'Other'],
            ['value' => 'tld', 'text' => 'TLD', 'group_name' => 'Other'],
        ];
        
        return response()->json([
            'message' => 'Supported field types',
            'total' => count($types),
            'types' => $types
        ]);
    }

    /**
     * Handle the auto form submission.
     * POST request only, requires API key authentication.
     */
    public function store(AutoFormRequest $request): JsonResponse
    {
        // Get validated data (AutoFormRequest handles validation)
        $validated = $request->validated();
        $fields = $validated['fields'];
        $locale = $this->getValidatedLocale($validated['locale'] ?? null);
        $generator = $validated['generator'] ?? 'faker';
        
        // Set app locale
        app()->setLocale($locale);
        
        // Generate test data based on generator type
        if ($generator === 'ai') {
            // Phân loại fields: chỉ dùng AI cho text/email/textarea/search
            [$aiFields, $fakerFields] = $this->separateFieldsByType($fields);
            
            $testData = [];
            $fakerLocale = $this->getFakerLocale($locale);
            $faker = $this->getFakerInstance($locale);
            
            // Generate với Faker cho các field không cần AI
            if (!empty($fakerFields)) {
                $fakerData = $this->generateFieldsData($fakerFields, $faker, $fakerLocale);
                $testData = array_merge($testData, $fakerData);
            }
            
            // Generate với AI cho các field văn bản
            if (!empty($aiFields)) {
                try {
                    $aiGenerator = new AIFormDataGenerator();
                    $aiData = $aiGenerator->generate($aiFields, $locale);
                    
                    // Validate AI response
                    if (!is_array($aiData)) {
                        throw new \RuntimeException('AI generator trả về dữ liệu không hợp lệ');
                    }
                    
                    $testData = array_merge($testData, $aiData);
                } catch (\Exception $e) {
                    // Fallback to Faker nếu AI fail
                    Log::warning('AI generation failed, falling back to Faker', [
                        'error' => $e->getMessage(),
                        'ai_fields_count' => count($aiFields),
                    ]);
                    $fakerData = $this->generateFieldsData($aiFields, $faker, $fakerLocale);
                    $testData = array_merge($testData, $fakerData);
                }
            }
            
            return response()->json([
                'message' => 'Test data generated successfully',
                'locale' => $locale,
                'test_data' => $testData
            ]);
        }
        
        // Use Faker (default)
        $testData = $this->generateTestData($fields, $locale);
        
        return response()->json([
            'message' => 'Test data generated successfully',
            'locale' => $locale,
            'test_data' => $testData
        ]);
    }

    /**
     * Separate fields into AI fields (text-based) and Faker fields (simple types).
     *
     * @param array $fields
     * @return array [aiFields, fakerFields]
     */
    private function separateFieldsByType(array $fields): array
    {
        // Field types cần AI (văn bản, cần tư duy)
        $aiFieldTypes = ['text', 'email', 'textarea', 'search', 'input'];
        
        $aiFields = [];
        $fakerFields = [];
        
        foreach ($fields as $field) {
            $type = strtolower($field['type'] ?? 'text');
            
            if (in_array($type, $aiFieldTypes)) {
                $aiFields[] = $field;
            } else {
                $fakerFields[] = $field;
            }
        }
        
        return [$aiFields, $fakerFields];
    }

    /**
     * Generate test data for specific fields using Faker.
     *
     * @param array $fields
     * @param \Faker\Generator $faker
     * @param string $fakerLocale
     * @return array
     */
    private function generateFieldsData(array $fields, $faker, string $fakerLocale): array
    {
        $testData = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            if (!$name) {
                continue;
            }

            $type = strtolower($field['type'] ?? 'text');
            $testData[$name] = $this->generateValueByType($type, $field, $faker, $fakerLocale);
        }

        return $testData;
    }

    /**
     * Generate test data based on form fields using Faker.
     *
     * @param array $fields
     * @param string|null $locale
     * @return array
     */
    private function generateTestData(array $fields, ?string $locale = null): array
    {
        $faker = $this->getFakerInstance($locale);
        $fakerLocale = $this->getFakerLocale($locale);
        return $this->generateFieldsData($fields, $faker, $fakerLocale);
    }

    /**
     * Generate a test value based on field type.
     *
     * @param string $type
     * @param array $field
     * @param \Faker\Generator $faker
     * @param string $fakerLocale
     * @return mixed
     */
    private function generateValueByType(string $type, array $field, $faker, string $fakerLocale)
    {
        return match ($type) {
            // Email types
            'email' => $faker->safeEmail(),
            'email-safe' => $faker->safeEmail(),
            'email-free' => $faker->freeEmail(),
            'email-company' => $faker->companyEmail(),
            
            // Password
            'password' => $this->generatePassword($field, $faker),
            
            // Phone
            'tel', 'phone', 'telephone' => $faker->phoneNumber(),
            
            // URL & Domain
            'url' => $faker->url(),
            'domain', 'domain-name' => $faker->domainName(),
            'slug' => $faker->slug(),
            
            // Numbers
            'number', 'numeric' => $this->generateNumber($field, $faker),
            'random-digit' => $faker->randomDigit(),
            'random-digit-not-zero' => $faker->randomDigitNotNull(),
            'random-number' => $faker->randomNumber(),
            'random-float' => $faker->randomFloat(2, 0, 1000),
            'range' => $this->generateRange($field, $faker),
            
            // Date & Time
            'date' => $faker->date('Y-m-d'),
            'datetime', 'datetime-local' => $faker->dateTime()->format('Y-m-d\TH:i'),
            'time' => $faker->time('H:i'),
            'month' => $faker->date('Y-m'),
            'week' => $faker->date('Y-\WW'),
            'unix-time' => $faker->unixTime(),
            'iso8601' => $faker->iso8601(),
            
            // Color
            'color' => $faker->hexColor(),
            'hex-color' => $faker->hexColor(),
            'safe-hex-color' => $faker->safeHexColor(),
            'rgb-color' => $faker->rgbColor(),
            'rgb-css-color' => $faker->rgbCssColor(),
            'rgba-css-color' => $faker->rgbaCssColor(),
            'hsl-color' => $faker->hslColor(),
            
            // Boolean
            'checkbox' => $faker->boolean(),
            'boolean' => $faker->boolean(),
            
            // Select & Radio
            'radio' => $this->getRandomOption($field, $faker),
            'select' => $this->getRandomOption($field, $faker),
            
            // Text types
            'textarea' => $this->generateLocalizedParagraph($faker, $fakerLocale, 3, $field),
            'search' => $this->generateLocalizedWords($faker, $fakerLocale, 3, $field),
            'text', 'input' => $this->generateLocalizedText($faker, $fakerLocale, 50, $field),
            'word' => $faker->word(),
            'words' => implode(' ', $faker->words(3)),
            'sentence' => $faker->sentence(),
            'paragraph' => $faker->paragraph(),
            
            // File
            'file', 'file-upload' => [
                'name' => $faker->word() . '.' . $faker->fileExtension(),
                'size' => $faker->numberBetween(1000, 5000000),
                'type' => $faker->mimeType(),
            ],
            'mime-type' => $faker->mimeType(),
            'file-extension' => $faker->fileExtension(),
            
            // Network
            'ip', 'ipv4' => $faker->ipv4(),
            'ipv6' => $faker->ipv6(),
            'local-ipv4' => $faker->localIpv4(),
            'mac-address' => $faker->macAddress(),
            'user-agent' => $faker->userAgent(),
            
            // Payment
            'credit-card', 'creditcard' => $faker->creditCardNumber(),
            'credit-card-type' => $faker->creditCardType(),
            'credit-card-expiration' => $faker->creditCardExpirationDateString(),
            'iban' => $faker->iban(),
            'swift-bic' => $faker->swiftBicNumber(),
            
            // Identifiers
            'hidden' => $faker->uuid(),
            'uuid' => $faker->uuid(),
            'ean13' => $faker->ean13(),
            'ean8' => $faker->ean8(),
            'isbn10' => $faker->isbn10(),
            'isbn13' => $faker->isbn13(),
            'barcode' => $faker->ean13(),
            
            // Hash
            'md5' => $faker->md5(),
            'sha1' => $faker->sha1(),
            'sha256' => $faker->sha256(),
            
            // Location
            'country-code' => $faker->countryCode(),
            'country-iso-alpha3' => $faker->countryISOAlpha3(),
            'language-code' => $faker->languageCode(),
            'currency-code' => $faker->currencyCode(),
            'timezone' => $faker->timezone(),
            
            // Other
            'emoji' => $faker->emoji(),
            'semver', 'version' => $faker->semver(),
            'username', 'user-name' => $faker->userName(), // user-name được map về username
            'tld' => $faker->tld(),
            
            // Default fallback
            default => $this->generateLocalizedText($faker, $fakerLocale, 50, $field),
        };
    }

    /**
     * Get a random option from field options or return default.
     *
     * @param array $field
     * @param \Faker\Generator $faker
     * @return string
     */
    private function getRandomOption(array $field, $faker): string
    {
        $options = $field['options'] ?? null;
        
        if (is_array($options) && !empty($options)) {
            // Filter out null values and get valid options
            $validOptions = array_filter($options, fn($option) => $option !== null);
            if (!empty($validOptions)) {
                return $faker->randomElement(array_values($validOptions));
            }
        }
        
        // Default fallback options
        return $faker->randomElement(['option1', 'option2', 'option3']);
    }

    /**
     * Get validated locale from whitelist or return default.
     *
     * @param string|null $locale
     * @return string
     */
    private function getValidatedLocale(?string $locale): string
    {
        if ($locale && isset(self::ALLOWED_LOCALES[$locale])) {
            return $locale;
        }

        return self::DEFAULT_LOCALE;
    }

    /**
     * Get Faker instance with specified locale.
     * Falls back to en_US if the requested locale is not supported by Faker.
     *
     * @param string|null $locale
     * @return \Faker\Generator
     */
    private function getFakerInstance(?string $locale = null): \Faker\Generator
    {
        $fakerLocale = $this->getFakerLocale($locale);
        
        // Try to create Faker with requested locale
        try {
            return \Faker\Factory::create($fakerLocale);
        } catch (\Exception $e) {
            // If locale creation fails, fallback to en_US
            return \Faker\Factory::create('en_US');
        }
    }

    /**
     * Get the actual Faker locale that will be used.
     *
     * @param string|null $locale
     * @return string
     */
    private function getFakerLocale(?string $locale = null): string
    {
        $locale = $locale ?? self::DEFAULT_LOCALE;
        $fakerLocale = self::ALLOWED_LOCALES[$locale] ?? self::ALLOWED_LOCALES[self::DEFAULT_LOCALE];
        
        // All locales in ALLOWED_LOCALES are supported, but check anyway for safety
        if (!in_array($fakerLocale, self::SUPPORTED_FAKER_LOCALES)) {
            $fakerLocale = 'en_US';
        }
        
        return $fakerLocale;
    }

    /**
     * Generate localized text based on Faker locale support.
     *
     * @param \Faker\Generator $faker
     * @param string $fakerLocale
     * @param int $defaultMaxChars Default maximum characters
     * @param array $field Field configuration
     * @return string
     */
    private function generateLocalizedText($faker, string $fakerLocale, int $defaultMaxChars = 50, array $field = []): string
    {
        $minLength = isset($field['min_length']) ? max(1, (int)$field['min_length']) : null;
        $maxLength = isset($field['max_length']) ? (int)$field['max_length'] : $defaultMaxChars;
        
        // Ensure maxLength >= minLength
        if ($minLength !== null && $maxLength < $minLength) {
            $maxLength = $minLength;
        }
        
        // For non-English locales, try to use realText (it's a magic method, so we can't use method_exists)
        if ($fakerLocale !== 'en_US') {
            try {
                // Generate enough text to meet min_length if specified
                $targetLength = $minLength !== null ? max($minLength, $maxLength) : $maxLength;
                $text = $faker->realText($targetLength);
                $text = mb_substr($text, 0, $maxLength);
                
                // Ensure minimum length by generating more if needed
                $attempts = 0;
                while ($minLength !== null && mb_strlen($text) < $minLength && $attempts < 10) {
                    $needed = $minLength - mb_strlen($text);
                    $maxAdditional = $maxLength !== null ? min($needed, $maxLength - mb_strlen($text)) : $needed;
                    if ($maxAdditional <= 0) {
                        break;
                    }
                    $additional = $faker->realText($maxAdditional + 20);
                    $text .= ' ' . mb_substr($additional, 0, $maxAdditional);
                    if ($maxLength !== null) {
                        $text = mb_substr($text, 0, $maxLength);
                    }
                    $attempts++;
                }
                
                return $text;
            } catch (\Exception $e) {
                // Fallback to sentence if realText fails
            }
        }
        
        // Fallback to sentence for English or if realText fails
        $text = $faker->sentence(3);
        $text = mb_substr($text, 0, $maxLength);
        
        // Ensure minimum length by generating more sentences if needed
        $attempts = 0;
        while ($minLength !== null && mb_strlen($text) < $minLength && $attempts < 10) {
            $needed = $minLength - mb_strlen($text);
            $maxAdditional = $maxLength !== null ? min($needed, $maxLength - mb_strlen($text)) : $needed;
            if ($maxAdditional <= 0) {
                break;
            }
            $additional = $faker->sentence(2);
            $text .= ' ' . mb_substr($additional, 0, $maxAdditional);
            if ($maxLength !== null) {
                $text = mb_substr($text, 0, $maxLength);
            }
            $attempts++;
        }
        
        return $text;
    }

    /**
     * Generate localized words based on Faker locale support.
     *
     * @param \Faker\Generator $faker
     * @param string $fakerLocale
     * @param int $defaultCount Default number of words
     * @param array $field Field configuration
     * @return string
     */
    private function generateLocalizedWords($faker, string $fakerLocale, int $defaultCount = 3, array $field = []): string
    {
        $minLength = isset($field['min_length']) ? max(1, (int)$field['min_length']) : null;
        $maxLength = isset($field['max_length']) ? (int)$field['max_length'] : null;
        
        // Estimate word count based on length constraints
        $count = $defaultCount;
        if ($maxLength !== null) {
            // Rough estimate: average word length ~5-7 chars + space
            $count = max(1, (int)($maxLength / 6));
        }
        
        // For non-English locales, try to use realText and extract words
        if ($fakerLocale !== 'en_US') {
            try {
                $text = $faker->realText($maxLength ?? 50);
                $words = preg_split('/\s+/u', $text);
                $words = array_filter($words, fn($w) => mb_strlen($w) > 0);
                if (count($words) >= $count) {
                    $result = implode(' ', array_slice($words, 0, $count));
                    
                    // Apply length constraints
                    if ($maxLength !== null && mb_strlen($result) > $maxLength) {
                        $result = mb_substr($result, 0, $maxLength);
                    }
                    // Ensure minimum length by adding more words if needed
                    $attempts = 0;
                    while ($minLength !== null && mb_strlen($result) < $minLength && $attempts < 20) {
                        $additional = $faker->words(1, true);
                        $result .= ' ' . $additional;
                        if ($maxLength !== null && mb_strlen($result) > $maxLength) {
                            $result = mb_substr($result, 0, $maxLength);
                            break;
                        }
                        $attempts++;
                    }
                    
                    return $result;
                }
            } catch (\Exception $e) {
                // Fallback to words
            }
        }
        
        // Fallback to words for English or if realText fails
        $result = $faker->words($count, true);
        
        // Apply length constraints
        if ($maxLength !== null && mb_strlen($result) > $maxLength) {
            $result = mb_substr($result, 0, $maxLength);
        }
        // Ensure minimum length by adding more words if needed
        $attempts = 0;
        while ($minLength !== null && mb_strlen($result) < $minLength && $attempts < 20) {
            $additional = $faker->words(1, true);
            $result .= ' ' . $additional;
            if ($maxLength !== null && mb_strlen($result) > $maxLength) {
                $result = mb_substr($result, 0, $maxLength);
                break;
            }
            $attempts++;
        }
        
        return $result;
    }

    /**
     * Generate localized paragraph based on Faker locale support.
     *
     * @param \Faker\Generator $faker
     * @param string $fakerLocale
     * @param int $defaultSentences Default number of sentences
     * @param array $field Field configuration
     * @return string
     */
    private function generateLocalizedParagraph($faker, string $fakerLocale, int $defaultSentences = 3, array $field = []): string
    {
        $minLength = isset($field['min_length']) ? max(1, (int)$field['min_length']) : null;
        $maxLength = isset($field['max_length']) ? (int)$field['max_length'] : null;
        
        // Estimate sentences based on maxLength
        $sentences = $defaultSentences;
        if ($maxLength !== null) {
            // Rough estimate: average sentence length ~50-100 chars
            $sentences = max(1, (int)($maxLength / 75));
        }
        
        // For non-English locales, try to use realText (it's a magic method)
        if ($fakerLocale !== 'en_US') {
            try {
                // Generate text with approximate length for sentences
                $text = $faker->realText($maxLength ?? ($sentences * 50));
                
                // Apply length constraints
                if ($maxLength !== null && mb_strlen($text) > $maxLength) {
                    $text = mb_substr($text, 0, $maxLength);
                }
                // Ensure minimum length by generating more if needed
                $attempts = 0;
                while ($minLength !== null && mb_strlen($text) < $minLength && $attempts < 10) {
                    $needed = $minLength - mb_strlen($text);
                    $maxAdditional = $maxLength !== null ? min($needed, $maxLength - mb_strlen($text)) : $needed;
                    if ($maxAdditional <= 0) {
                        break;
                    }
                    $additional = $faker->realText($maxAdditional + 20);
                    $text .= ' ' . mb_substr($additional, 0, $maxAdditional);
                    if ($maxLength !== null && mb_strlen($text) > $maxLength) {
                        $text = mb_substr($text, 0, $maxLength);
                        break;
                    }
                    $attempts++;
                }
                
                return $text;
            } catch (\Exception $e) {
                // Fallback to paragraph if realText fails
            }
        }
        
        // Fallback to paragraph for English or if realText fails
        $text = $faker->paragraph($sentences);
        
        // Apply length constraints
        if ($maxLength !== null && mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength);
        }
        // Ensure minimum length by generating more sentences if needed
        $attempts = 0;
        while ($minLength !== null && mb_strlen($text) < $minLength && $attempts < 10) {
            $needed = $minLength - mb_strlen($text);
            $maxAdditional = $maxLength !== null ? min($needed, $maxLength - mb_strlen($text)) : $needed;
            if ($maxAdditional <= 0) {
                break;
            }
            $additional = $faker->paragraph(1);
            $text .= ' ' . mb_substr($additional, 0, $maxAdditional);
            if ($maxLength !== null && mb_strlen($text) > $maxLength) {
                $text = mb_substr($text, 0, $maxLength);
                break;
            }
            $attempts++;
        }
        
        return $text;
    }

    /**
     * Generate password with min/max length constraints.
     *
     * @param array $field
     * @param \Faker\Generator $faker
     * @return string
     */
    private function generatePassword(array $field, $faker): string
    {
        $minLength = isset($field['min_length']) ? max(1, (int)$field['min_length']) : 8;
        $maxLength = isset($field['max_length']) ? (int)$field['max_length'] : 20;
        
        // Ensure maxLength >= minLength
        if ($maxLength < $minLength) {
            $maxLength = $minLength;
        }
        
        return $faker->password($minLength, $maxLength);
    }

    /**
     * Generate number with min/max constraints.
     *
     * @param array $field
     * @param \Faker\Generator $faker
     * @return int
     */
    private function generateNumber(array $field, $faker): int
    {
        $min = isset($field['min']) ? (int)$field['min'] : 1;
        $max = isset($field['max']) ? (int)$field['max'] : 1000;
        
        // Ensure max >= min
        if ($max < $min) {
            $max = $min;
        }
        
        return $faker->numberBetween($min, $max);
    }

    /**
     * Generate range value with min/max constraints.
     *
     * @param array $field
     * @param \Faker\Generator $faker
     * @return int
     */
    private function generateRange(array $field, $faker): int
    {
        $min = isset($field['min']) ? (int)$field['min'] : 0;
        $max = isset($field['max']) ? (int)$field['max'] : 100;
        
        // Ensure max >= min
        if ($max < $min) {
            $max = $min;
        }
        
        return $faker->numberBetween($min, $max);
    }
}

