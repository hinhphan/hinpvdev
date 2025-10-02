<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Node\NodeResource;
use App\Models\Node;
use Illuminate\Http\Request;
use App\Services\Node\CreateNode;
use App\Services\Node\UpdateNode;
use App\Services\Node\DestroyNode;

class NodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nodes = Node::all();

        return $this->responseSuccess([
            'items' => NodeResource::collection($nodes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $node = app(CreateNode::class)->execute($request->all());
        return $this->responseSuccess(NodeResource::make($node)->resolve());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $node = Node::findOrFail($id);
        return $this->responseSuccess(NodeResource::make($node)->resolve());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $node = app(UpdateNode::class)->execute([...$request->all(), ...['id' => $id]]);
        return $this->responseSuccess(NodeResource::make($node)->resolve());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        app(DestroyNode::class)->execute(['id' => $id]);
        return $this->responseSuccess();
    }
}
