<?php

namespace App\Services\Role;

use App\Enums\Boolean;
use App\Enums\FeatureCode;
use App\Enums\PermissionCode;
use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class CreateRole extends BaseService
{
    protected $featureCode = FeatureCode::ROLE_MANAGEMENT;
    protected $permissionCode = PermissionCode::CREATE;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:roles,name'
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
        parent::execute($data);

        return $this->createRole($data);
    }

    /**
     * Create a new role.
     * @param array $data
     * @return Role
     */
    private function createRole(array $data)
    {
        return Role::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'is_admin' => $data['is_admin'],
        ]);
    }
}