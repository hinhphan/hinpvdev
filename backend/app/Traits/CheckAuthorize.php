<?php

namespace App\Traits;

use App\Exceptions\ServiceAccessForbiddenException;

trait CheckAuthorize
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
     * Summary of skipCheckAuthorize
     * @var bool
     */
    protected $skipCheckAuthorize = false;

    public function checkAuthorize()
    {
        // Skip check authorization if not set featureCode or permissionCode or skipCheckAuthorize is true
        if ($this->skipCheckAuthorize || empty($this->featureCode) || empty($this->permissionCode)) {
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