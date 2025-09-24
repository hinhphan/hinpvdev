<?php

namespace App\Http\Requests\Api\Pagination;

use App\Http\Requests\Api\BaseRequest;

class PaginationRequest extends BaseRequest
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
        ];
    }

    public function getPaginationSize()
    {
        return $this->input('size', config('constants.pagination_default_size', 10));
    }
}
