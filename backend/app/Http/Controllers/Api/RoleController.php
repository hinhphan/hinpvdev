<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Pagination\PaginationRequest;
use App\Http\Resources\Pagination\PaginationResource;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\Role\CreateRole;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginationRequest $request)
    {
        $roles = Role::paginate($request->getPaginationSize());

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
        $role = app(CreateRole::class)->execute($request->all());

        return $this->responseSuccess(RoleResource::make($role)->resolve());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
