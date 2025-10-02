<?php

namespace App\Services\Node;

use App\Models\Node;
use App\Services\BaseService;

class DestroyNode extends BaseService
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
        ];
    }

    public function execute(array $data): bool
    {
        $this->validate($data);

        $node = Node::findOrFail($data['id']);

        $node->delete();

        return true;
    }
}