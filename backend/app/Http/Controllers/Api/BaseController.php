<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use JsonRespondController;
}
