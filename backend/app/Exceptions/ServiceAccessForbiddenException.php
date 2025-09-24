<?php

namespace App\Exceptions;

use Exception;

class ServiceAccessForbiddenException extends Exception
{
    protected $message = 'Access to the service is forbidden.';
}