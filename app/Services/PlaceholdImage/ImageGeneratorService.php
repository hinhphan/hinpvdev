<?php

namespace App\Services\PlaceholdImage;

use App\Helpers\ColorHelper;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\File;

class ImageGeneratorService extends BaseService {

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
    
    public function execute(array $data): File {
        $this->validate($data);

        $img = @imagecreate($data['width'], $data['height']);

        if ($img === false) throw new Exception('Can\'t create image.');

        // Background
        $bg = ColorHelper::hexToRgb($data['bg'] ?? '#FFFFFF');
        imagecolorallocate($img, $bg['r'], $bg['g'], $bg['b']);

        // Color
        $color = ColorHelper::hexToRgb($data['color'] ?? '#000000');
        $textColor = imagecolorallocate($img, $color['r'], $color['g'], $color['b']);

        // Text
        $fontPath = public_path('fonts/MSGothic.ttf');
        $text = $data['text'] ?? '';
        $fontSize = $this->getMaxFontSize($text, $fontPath, $data['width'] - 20, $data['height'] - 20);
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = abs($box[2] - $box[0]);
        $textHeight = abs($box[5] - $box[1]);
        $x = ($data['width'] - $textWidth) / 2;
        $y = ($data['height'] + $textHeight) / 2;

        imagettftext($img, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);

        // Output File
        $tmpPath = tempnam(sys_get_temp_dir(), 'img_') . '.png';
        imagepng($img, $tmpPath);

        imagedestroy($img);

        return new File($tmpPath);
    }

    protected function getMaxFontSize($text, $fontPath, $maxWidth, $maxHeight, $min = 5, $max = 100) {
        for ($size = $min; $size <= $max; $size++) {
            $box = imagettfbbox($size, 0, $fontPath, $text);
            $textWidth = abs($box[2] - $box[0]);
            $textHeight = abs($box[5] - $box[1]);

            if ($textWidth > $maxWidth || $textHeight > $maxHeight) {
                return $size - 1;
            }
        }
    return $max;
}
}