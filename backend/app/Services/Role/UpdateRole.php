<?php

namespace App\Services\Role;

use App\Enums\Boolean;
use App\Helpers\ArrayValueHelper;
use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class UpdateRole extends BaseService
{
    /**
     * The ID of the role to update.
     * @var int | null
     */
    protected $roleId = null;

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
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:roles,name,' . $this->roleId
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_admin' => [
                'required',
                Rule::in(Boolean::values()),
            ],
        ];
    }

    public function execute(array $data): Role
    {
        $this->roleId = $data['id'];

        $this->validate($data);

        $role = Role::findOrFail($data['id']);

        $role->update([
            'name' => $data['name'],
            'description' => ArrayValueHelper::nullOrValue($data, 'description'),
            'is_admin' => ArrayValueHelper::valueOrBoolean($data, 'is_admin'),
        ]);

        return $role;
    }
}