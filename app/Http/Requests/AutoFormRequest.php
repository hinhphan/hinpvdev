<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $apiKey = $this->header('X-API-Key') ?? $this->input('api_key');
        $validApiKey = config('services.auto_forms.api_key') ?? env('AUTO_FORMS_API_KEY');
        
        if (!$validApiKey) {
            return false; // Không có API key được cấu hình
        }
        
        return $apiKey === $validApiKey;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'locale' => 'nullable|string|in:en,vi,ja,fr,de,es,it,pt,ru,ar',
            'generator' => 'nullable|string|in:faker,ai',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'nullable|string',
            'fields.*.id' => 'nullable|string',
            'fields.*.class' => 'nullable|string',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.value' => 'nullable|string',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*' => 'nullable|string',
            'fields.*.min' => 'nullable|numeric',
            'fields.*.max' => 'nullable|numeric',
            'fields.*.min_length' => 'nullable|integer|min:0',
            'fields.*.max_length' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'fields.required' => 'Trường fields là bắt buộc.',
            'fields.array' => 'Trường fields phải là một mảng.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization(): void
    {
        abort(403, 'Unauthorized: API key không hợp lệ hoặc thiếu. Vui lòng cung cấp X-API-Key header hoặc api_key trong request body.');
    }
}

