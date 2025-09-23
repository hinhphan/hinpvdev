<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class BaseService {

    /**
     * Summary of featureName
     * @var string
     */
    protected $featureCode = '';

    /**
     * Summary of permissionCode
     * @var string
     */
    protected $permissionCode = '';

    /**
     * Summary of skipCheckPermission
     * @var bool
     */
    protected $skipCheckPermission = false;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validate all datas to execute the service.
     *
     * @param  array  $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        Validator::make($data, $this->rules())
            ->validate();

        return true;
    }

    /**
     * Summary of execute
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $this->checkPermission();
        $this->validate($data);
    }

    public function checkPermission(): bool
    {
        if ($this->skipCheckPermission) {
            return true;
        }

        $features = $this->listFeaturePermissionByAuth();

        return true;
    }

    private function listFeaturePermissionByAuth(): array
    {
        if (!auth()->check()) return [];

        $user = auth()->user();
        $user->load(['roles.permissions.feature', 'permissions.feature']);
        $roles = $user->roles;
ddd($roles);
        $features = [];
        $permissions = $user->permissions->concat($roles->pluck('permissions')->all())->groupBy('feature_id');
ddd($user);

        return $features;
    }
}