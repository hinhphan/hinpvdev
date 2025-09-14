<?php

namespace App\Enums;

class Boolean {
    public const TRUE = 1;
    public const FALSE = 0;

    public static function values(): array
    {
        return [
            self::TRUE,
            self::FALSE,
        ];
    }
}