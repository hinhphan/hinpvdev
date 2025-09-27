<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Role\IndexRequest;
use App\Http\Requests\Api\Role\ShowRequest;
use App\Http\Requests\Api\Role\StoreRequest;
use App\Http\Requests\Api\Role\UpdateRequest;
use App\Http\Resources\Pagination\PaginationResource;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\Role\CreateRole;
use App\Services\Role\UpdateRole;
use App\Services\Role\DestroyRole;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
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
    public function store(StoreRequest $request)
    {
        $role = app(CreateRole::class)->execute($request->all());
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $role = app(UpdateRole::class)->execute([...$request->all(), ...['id' => $id]]);
        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        app(DestroyRole::class)->execute(['id' => $id]);
        return $this->responseSuccess();
    }
}
