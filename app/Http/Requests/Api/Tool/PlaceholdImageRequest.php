<?php

namespace App\Http\Requests\Api\Tool;

use App\Http\Requests\Api\BaseRequest;

class PlaceholdImageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'w' => 'required|int|min:0|max:5000',
            'h' => 'required|int|min:0|max:5000',
            'txt' => 'nullable|string|max:50',
            'c' => 'nullable|hex_color',
            'bg' => 'nullable|hex_color',
        ];
    }
}
