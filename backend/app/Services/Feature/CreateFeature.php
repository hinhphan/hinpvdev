<?php

namespace App\Services\Feature;

use App\Helpers\ArrayValueHelper;
use App\Models\Feature;
use App\Services\BaseService;
use Arr;

class CreateFeature extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:features,code'
            ],
            'name'=> [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function execute(array $data): Feature
    {
        $this->validate($data);

        return Feature::create([
            'code'=> $data['code'],
            'name'=> $data['name'],
            'description'=> ArrayValueHelper::nullOrValue($data, 'description'),
        ]);
    }
}