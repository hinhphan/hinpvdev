<?php

namespace App\Services\Node;

use App\Helpers\ArrayValueHelper;
use App\Models\Node;
use App\Services\BaseService;

class CreateNode extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function execute(array $data): Node
    {
        $this->validate($data);

        return Node::create([
            'label' => $data['label'],
            'description' => ArrayValueHelper::nullOrValue($data, 'description'),
        ]);
    }
}