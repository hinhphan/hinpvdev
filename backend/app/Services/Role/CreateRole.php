<?php

namespace App\Services\Role;

use App\Enums\FeatureCode;
use App\Enums\PermissionCode;
use App\Services\BaseService;

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
            
        ];
    }

    public function execute(array $data)
    {
        parent::execute($data);

        
    }
}