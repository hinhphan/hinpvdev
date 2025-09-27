<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Feature\FeatureResource;
use App\Http\Resources\Pagination\PaginationResource;
use App\Models\Feature;
use App\Services\Feature\CreateFeature;
use Illuminate\Http\Request;

class FeatureController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $feature = app(CreateFeature::class)->execute($request->all());
        return $this->responseSuccess(FeatureResource::make($feature)->resolve());
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
