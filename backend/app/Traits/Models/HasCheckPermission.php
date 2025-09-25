<?php

namespace App\Traits\Models;

use App\Enums\Boolean;
use Illuminate\Support\Arr;

trait HasCheckPermission
{
    /**
     * Summary of hasPermission
     * @param string $featureCode
     * @param string $permissionCode
     * @return bool
     */
    public function hasPermission(string $featureCode, string $permissionCode = null): bool
    {
        $this->load(['roles.permissions.feature', 'permissions.feature']);

        $roles = $this->roles;
        
        // If user has admin role, allow all permissions
        if ($roles->where('is_admin', Boolean::TRUE)->count() > 0) {
            return true;
        }

        $permissions = $this->roles->pluck('permissions')->flatten()->merge($this->permissions->toArray());
        $features = $permissions->groupBy('feature.code')->toArray();

        // If feature not exists, deny permission
        if (!isset($features[$featureCode])) {
            return false;
        }

        $permissions = $features[$featureCode];
        $actions = Arr::pluck($permissions, 'action');

        // If permission code not exists, deny permission
        if ($permissionCode && !in_array($permissionCode, $actions)) {
            return false;
        }

        return true;
    }
}