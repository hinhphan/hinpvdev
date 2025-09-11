<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceholdImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'params' => $this->params,
            'params_formatted' => json_decode($this->params),
            'url' => route('get.placehold.image.preview', [
                'id' => $this->id,
            ]),
        ];
    }
}
