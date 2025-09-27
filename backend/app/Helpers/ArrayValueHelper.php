<?php

namespace App\Helpers;

use App\Enums\Boolean;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ArrayValueHelper
{
    /**
     * Get the value from the array or return null.
     * @param mixed $data
     * @param mixed $index
     */
    public static function nullOrValue($data, $index)
    {
        $value = Arr::get($data, $index, null);

        return is_null($value) || $value === '' ? null : $value;
    }

    /**
     * Get the date from the array or return null.
     * @param mixed $data
     * @param mixed $index
     * @return Carbon|null
     */
    public static function nullOrDate($data, $index)
    {
        $value = Arr::get($data, $index, null);

        return is_null($value) || $value === '' ? null : Carbon::parse($value);
    }

    /**
     * Get the value from the array or return Enum Boolean.
     * @param mixed $data
     * @param mixed $index
     */
    public static function valueOrBoolean($data, $index)
    {
        $value = Arr::get($data, $index, null);

        return is_null($value) || $value === '' || empty($value) ? Boolean::FALSE : Boolean::TRUE;
    }
}