<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutoFormRequest;
use Illuminate\Http\JsonResponse;

class AutoFormController extends Controller
{
    /**
     * Whitelist of allowed locales.
     * Maps client locale to Faker locale.
     * Only locales with full Faker support are included.
     */
    private const ALLOWED_LOCALES = [
        'en' => 'en_US', // English
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
        'en_US', 'ja_JP', 'fr_FR', 'de_DE', 'es_ES', 'it_IT', 'pt_BR', 'ru_RU', 'ar_SA'
    ];

    /**
     * Default locale (fallback).
     */
    private const DEFAULT_LOCALE = 'en';

    /**
     * Handle the auto form submission.
     * Supports both GET (query string) and request body.
     */
    public function store(AutoFormRequest $request): JsonResponse
    {
        // Get validated data (AutoFormRequest handles validation)
        $validated = $request->validated();
        $fields = $validated['fields'];
        $locale = $this->getValidatedLocale($validated['locale'] ?? null);
        
        // Set app locale
        app()->setLocale($locale);
        
        // Get Faker locale that will be used
        $fakerLocale = $this->getFakerLocale($locale);
        
        // Generate test data based on fields
        $testData = $this->generateTestData($fields, $locale);
        
        return response()->json([
            'message' => 'Auto form endpoint',
            'locale' => $locale,
            'faker_locale' => $fakerLocale,
            'test_data' => $testData
        ]);
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
        $testData = [];
        $faker = $this->getFakerInstance($locale);
        $fakerLocale = $this->getFakerLocale($locale);

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            $type = strtolower($field['type'] ?? 'text');
            
            if (!$name) {
                continue;
            }

            // Generate test data based on field type
            $testData[$name] = $this->generateValueByType($type, $field, $faker, $fakerLocale);
        }

        return $testData;
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
            'email' => $faker->safeEmail(),
            'password' => $this->generatePassword($field, $faker),
            'tel', 'phone' => $faker->phoneNumber(),
            'url' => $faker->url(),
            'number', 'numeric' => $this->generateNumber($field, $faker),
            'date' => $faker->date('Y-m-d'),
            'datetime', 'datetime-local' => $faker->dateTime()->format('Y-m-d\TH:i'),
            'time' => $faker->time('H:i'),
            'month' => $faker->date('Y-m'),
            'week' => $faker->date('Y-\WW'),
            'color' => $faker->hexColor(),
            'range' => $this->generateRange($field, $faker),
            'checkbox' => $faker->boolean(),
            'radio' => $this->getRandomOption($field, $faker),
            'select' => $this->getRandomOption($field, $faker),
            'textarea' => $this->generateLocalizedParagraph($faker, $fakerLocale, 3, $field),
            'file', 'file-upload' => [
                'name' => $faker->word() . '.' . $faker->fileExtension(),
                'size' => $faker->numberBetween(1000, 5000000),
                'type' => $faker->mimeType(),
            ],
            'hidden' => $faker->uuid(),
            'search' => $this->generateLocalizedWords($faker, $fakerLocale, 3, $field),
            'text', 'input' => $this->generateLocalizedText($faker, $fakerLocale, 50, $field),
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

