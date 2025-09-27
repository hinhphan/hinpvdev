<?php

namespace App\Http\Requests\Api;

use App\Enums\Boolean;
use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'size' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ],
            'is_get_all' => [
                'nullable',
                Rule::in(Boolean::values()),
            ],
            'sort' => [
                'nullable',
                'string',
            ],
        ];
    }
}
