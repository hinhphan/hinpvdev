<?php

namespace App\Services\Role;

use App\Models\Role;
use App\Services\BaseService;

class DestroyRole extends BaseService
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
                'exists:roles,id'
            ],
        ];
    }

    public function execute(array $data): bool
    {
        $this->validate($data);

        $role = Role::findOrFail($data['id']);

        $role->delete();

        return true;
    }
}