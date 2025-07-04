<?php

namespace App\Services\OAuth;

use App\Services\BaseService;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use Throwable;
use Illuminate\Support\Facades\Log;

class RevokeAccessToken extends BaseService {

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
    
    public function execute(array $data) {
        $this->validate($data);

        try {
            $oauthAccessTokenId = app(GetTokenIdFromToken::class)->execute([
                'token' => $data['access_token'],
            ]);

            if (empty($oauthAccessTokenId)) return false;

            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            if (!$tokenRepository->isAccessTokenRevoked($oauthAccessTokenId)) {
                $tokenRepository->revokeAccessToken($oauthAccessTokenId);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($oauthAccessTokenId);
            }

            return true;

        } catch (Throwable $th) {
            Log::error($th);
        }

        return false;
    }
}