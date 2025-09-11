<?php

namespace App\Services\OAuth;

use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\TokenRepository;
use Throwable;

class GetUserFromAccessToken extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'access_token' => 'required|string',
        ];
    }

    public function execute(array $data)
    {
        $this->validate($data);

        try {
            // Attempt to parse the JWT
            $oauthAccessTokenId = app(GetTokenIdFromToken::class)->execute([
                'token' => $data['access_token'],
            ]);

            if (empty($oauthAccessTokenId)) return null;

            $oauthAccessToken = app(TokenRepository::class)->find($oauthAccessTokenId);

            if (empty($oauthAccessToken)) return null;

            return $oauthAccessToken->user;

        } catch (Throwable $th) {
            Log::error($th);
        }

        return null;
    }
}