<?php

namespace App\Services\Feature;

use App\Models\Feature;
use App\Services\BaseService;

class DestroyFeature extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:features,id'
            ],
        ];
    }

    public function execute(array $data): bool
    {
        $this->validate($data);

        $feature = Feature::findOrFail($data['id']);

        $feature->delete();

        return true;
    }
}