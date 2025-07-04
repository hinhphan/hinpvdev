<?php

namespace App\Enums;

class ResponseCode {
    public const INTERNAL_SERVER_ERROR = '500000';
    public const METHOD_NOT_ALLOWED = '400005';
    public const NOT_FOUND = '400004';
    public const UNPROCESSABLE_ENTITY = '400022';
    public const BAD_REQUEST = '400000';
    public const SUCCESS = '200000';
    public const UNAUTHORIZED = '400001';
}