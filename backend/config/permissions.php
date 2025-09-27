<?php

use App\Enums\FeatureCode;
use App\Enums\PermissionCode;

return [
    FeatureCode::ROLE_MANAGEMENT => [
        PermissionCode::READ,
        PermissionCode::CREATE,
        PermissionCode::UPDATE,
        PermissionCode::DELETE,
    ],
    FeatureCode::FEATURE_MANAGEMENT => [
        PermissionCode::READ,
        PermissionCode::CREATE,
        PermissionCode::UPDATE,
        PermissionCode::DELETE,
    ],
];