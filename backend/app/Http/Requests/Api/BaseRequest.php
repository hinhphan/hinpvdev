<?php

namespace App\Http\Requests\Api;

use App\Traits\CheckAuthorize;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    use CheckAuthorize;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
