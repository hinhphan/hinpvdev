<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\Boolean;
use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'max:255',
            ],
            'remember_me' => [
                'nullable',
                Rule::in(Boolean::values()),
            ],
        ];
    }
}
