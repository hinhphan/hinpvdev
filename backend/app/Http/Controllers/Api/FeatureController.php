<?php

namespace App\Http\Controllers\Api;

use App\Enums\FeatureCode;
use App\Enums\PermissionCode;
use App\Http\Resources\Feature\FeatureResource;
use App\Http\Resources\Pagination\PaginationResource;
use App\Models\Feature;
use App\Services\Feature\CreateFeature;
use App\Services\Feature\DestroyFeature;
use App\Services\Feature\UpdateFeature;
use Illuminate\Http\Request;

class FeatureController extends BaseController
{
    protected $featureCode = FeatureCode::FEATURE_MANAGEMENT;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorize(permissionCode: PermissionCode::READ);

        $features = Feature::orderBy($this->getSortColumn(), $this->getSortDirection())
            ->paginate($this->getPaginationSize());

        return $this->responseSuccess([
            'pagination' => new PaginationResource($features),
            'items' => FeatureResource::collection($features)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::CREATE);

        $feature = app(CreateFeature::class)->execute($request->all());
        return $this->responseSuccess(FeatureResource::make($feature)->resolve());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::READ);

        $feature = Feature::findOrFail($id);
        return $this->responseSuccess(FeatureResource::make($feature)->resolve());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::UPDATE);

        $feature = app(UpdateFeature::class)->execute([...$request->all(), ...['id' => $id]]);
        return $this->responseSuccess(FeatureResource::make($feature)->resolve());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAuthorize(permissionCode: PermissionCode::DELETE);

        app(DestroyFeature::class)->execute(['id' => $id]);
        return $this->responseSuccess();
    }
}
