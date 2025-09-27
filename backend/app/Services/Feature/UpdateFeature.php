<?php

namespace App\Services\Feature;

use App\Helpers\ArrayValueHelper;
use App\Models\Feature;
use App\Services\BaseService;

class UpdateFeature extends BaseService
{
    /**
     * The ID of the feature to update.
     * @var int | null
     */
    protected $featureId = null;

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
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:features,code,' . $this->featureId
            ],
            'name' => [
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
        $this->featureId = $data['id'];

        $this->validate($data);

        $feature = Feature::findOrFail($data['id']);

        $feature->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => ArrayValueHelper::nullOrValue($data, 'description'),
        ]);

        return $feature;
    }
}