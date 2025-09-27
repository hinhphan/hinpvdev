<?php

namespace App\Http\Controllers\Api;

use App\Enums\FeatureCode;
use App\Enums\PermissionCode;
use App\Http\Resources\Pagination\PaginationResource;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\Role\CreateRole;
use App\Services\Role\UpdateRole;
use App\Services\Role\DestroyRole;

class RoleController extends BaseController
{
    protected $featureCode = FeatureCode::ROLE_MANAGEMENT;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorize(permissionCode: PermissionCode::READ);

        $roles = Role::orderBy($this->getSortColumn(), $this->getSortDirection())
            ->paginate($this->getPaginationSize());

        return $this->responseSuccess([
            'pagination' => new PaginationResource($roles),
            'items' => RoleResource::collection($roles)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::CREATE);

        $role = app(CreateRole::class)->execute($request->all());
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::READ);

        $role = Role::findOrFail($id);
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::UPDATE);

        $role = app(UpdateRole::class)->execute([...$request->all(), ...['id' => $id]]);
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::DELETE);

        app(DestroyRole::class)->execute(['id' => $id]);
        return $this->responseSuccess();
    }
}
