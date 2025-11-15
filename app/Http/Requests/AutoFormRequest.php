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
        return true;
    }

    /**
     * Prepare the data for validation.
     * Handle GET request with JSON body or query string.
     */
    protected function prepareForValidation(): void
    {
        // For GET requests, try to parse JSON body if present
        if ($this->isMethod('GET') && $this->header('Content-Type') === 'application/json') {
            $content = $this->getContent();
            if (!empty($content)) {
                $data = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    $this->merge($data);
                }
            }
        }
        
        // Also handle query parameter 'data' as JSON string
        if ($this->has('data') && !$this->has('fields')) {
            $data = json_decode($this->get('data'), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                $this->merge($data);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'locale' => 'nullable|string|in:en,ja,fr,de,es,it,pt,ru,ar',
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
}

