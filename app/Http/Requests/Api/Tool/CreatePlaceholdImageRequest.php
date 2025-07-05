<?php

namespace App\Http\Requests\Api\Tool;

use App\Http\Requests\Api\BaseRequest;

class CreatePlaceholdImageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'width' => 'required|int|min:0|max:5000',
            'height' => 'required|int|min:0|max:5000',
            'text' => 'nullable|string|max:50',
            'color' => 'nullable|hex_color',
            'bg' => 'nullable|hex_color',
        ];
    }
}
