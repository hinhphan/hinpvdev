<?php

namespace App\Services\Node;

use App\Helpers\ArrayValueHelper;
use App\Models\Node;
use App\Services\BaseService;

class UpdateNode extends BaseService
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
                'exists:nodes,id'
            ],
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

        $node = Node::findOrFail($data['id']);

        $node->update([
            'label' => $data['label'],
            'description' => ArrayValueHelper::nullOrValue($data, 'description'),
        ]);

        return $node;
    }
}