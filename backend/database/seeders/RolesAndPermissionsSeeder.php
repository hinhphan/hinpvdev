<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role
        Role::query()->delete();
        Role::insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Administrator with full permissions',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Guest',
                'description' => 'Guest user with limited permissions',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Feature && Permission
        Feature::query()->delete();
        Permission::query()->delete();
        $features = config('permissions');
        $featureId = 1;
        $permissionId = 1;

        foreach ($features as $featureCode => $permissions) {
            Feature::insert([
                'id'=> $featureId,
                'code' => $featureCode,
                'name' => ucwords(str_replace('_', ' ', strtolower($featureCode))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($permissions as $permission) {
                Permission::insert([
                    'id'=> $permissionId,
                    'feature_id' => $featureId,
                    'action' => $permission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $permissionId++;
            }

            $featureId++;
        }
    }
}
