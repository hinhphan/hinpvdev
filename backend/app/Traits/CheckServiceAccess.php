<?php

namespace App\Traits;

use App\Exceptions\ServiceAccessForbiddenException;

trait CheckServiceAccess
{
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

    public function checkPermission()
    {
        // Skip check permission if not set featureCode or permissionCode or skipCheckPermission is true
        if ($this->skipCheckPermission || empty($this->featureCode) || empty($this->permissionCode)) {
            return;
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            throw new ServiceAccessForbiddenException();
        }

        $user = auth()->user();

        // Check if user has permission
        if (!$user->hasPermission($this->featureCode, $this->permissionCode)) {
            throw new ServiceAccessForbiddenException();
        }
    }
}