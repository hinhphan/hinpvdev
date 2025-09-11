<?php

namespace App\Services\PlaceholdImage;

use App\Models\PlaceholdImage;
use App\Services\BaseService;

class CreatePlaceholdImageService extends BaseService {
    
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'width' => 'required|int|min:0|max:5000',
            'height' => 'required|int|min:0|max:5000',
            'text' => 'nullable|string|max:50',
            'color' => 'nullable|hex_color',
            'bg' => 'nullable|hex_color',
        ];
    }

    public function execute(array $data) {
        $this->validate($data);

        $params = json_encode($data);

        return PlaceholdImage::firstOrCreate([
            'params' => $params,
        ], $data);
    }
}