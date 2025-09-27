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

    public function checkAuthorize(bool $isThrowException = true, string $featureCode = '', string $permissionCode = '')
    {
        // Override featureCode and permissionCode if provided
        if (!empty($featureCode)) {
            $this->featureCode = $featureCode;
        }

        // Override permissionCode if provided
        if (!empty($permissionCode)) {
            $this->permissionCode = $permissionCode;
        }

        // Skip check authorization if not set featureCode or permissionCode or skipCheckAuthorize is true
        if ($this->skipCheckAuthorize || empty($this->featureCode) || empty($this->permissionCode)) {
            return true;
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            if ($isThrowException) {
                throw new ServiceAccessForbiddenException();
            }
            return false;
        }

        $user = auth()->user();

        // Check if user has permission
        if (!$user->hasPermission($this->featureCode, $this->permissionCode)) {
            if ($isThrowException) {
                throw new ServiceAccessForbiddenException();
            }
            return false;
        }

        return true;
    }
}