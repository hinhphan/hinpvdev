<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Auth;

class MeController extends BaseController
{

    public function basicInfo()
    {
        $user = Auth::user();
        return $this->responseSuccess([
            'user' => new UserResource($user)
        ]);
    }
}
