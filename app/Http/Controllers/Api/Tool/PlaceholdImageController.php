<?php

namespace App\Http\Controllers\Api\Tool;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Tool\PlaceholdImageRequest;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use App\Services\PlaceholdImage\ImageGeneratorService;

class PlaceholdImageController extends BaseController
{
    use JsonRespondController;

    public function preview(PlaceholdImageRequest $request) {
        $img = app(ImageGeneratorService::class)->execute([
            'width' => $request['w'],
            'height' => $request['h'],
            'text' => $request['txt'],
            'color' => $request['c'],
            'bg' => $request['bg'],
        ]);

        return response()->file($img, [
            'Cache-Control' => 'no-store',
        ]);
    }
}
