<?php

namespace App\Http\Controllers\Api\Tool;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Tool\CreatePlaceholdImageRequest;
use App\Http\Resources\PlaceholdImageResource;
use App\Models\PlaceholdImage;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use App\Services\PlaceholdImage\ImageGeneratorService;
use App\Services\PlaceholdImage\CreatePlaceholdImageService;
use Illuminate\Support\Arr;

class PlaceholdImageController extends BaseController
{
    use JsonRespondController;

    public function preview(int $id) {
        $placeholdImage = PlaceholdImage::findOrFail($id);
        $params = Arr::from(json_decode($placeholdImage->params));
        
        $img = app(ImageGeneratorService::class)->execute([
            'width' => $params['width'],
            'height' => $params['height'],
            'text' => $params['text'] ?? null,
            'color' => $params['color'] ?? null,
            'bg' => $params['bg'] ?? null,
        ]);

        return response()->file($img);
    }

    public function create(CreatePlaceholdImageRequest $request) {
        $placeholdImage = app(CreatePlaceholdImageService::class)
            ->execute($request->validated());

        return $this->responseSuccess([
            'placehold_image' => new PlaceholdImageResource($placeholdImage),
        ]);
    }

    public function random() {
        $randomPlaceholdImage = PlaceholdImage::inRandomOrder(now()->timestamp)->firstOrFail();

        return $this->responseSuccess([
            'placehold_image' => new PlaceholdImageResource($randomPlaceholdImage),
        ]); 
    }
}
