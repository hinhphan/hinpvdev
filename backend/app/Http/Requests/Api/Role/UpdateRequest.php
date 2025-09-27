<?php

namespace App\Http\Requests\Api\Role;

use App\Enums\FeatureCode;
use App\Enums\PermissionCode;
use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
{
    protected $featureCode = FeatureCode::ROLE_MANAGEMENT;
    protected $permissionCode = PermissionCode::UPDATE;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkAuthorize(false);
    }
}
