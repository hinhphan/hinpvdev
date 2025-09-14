<?php

namespace App\Http\Controllers\Api;

use App\Enums\Boolean;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Services\OAuth\PasswordGrantToken;
use App\Services\OAuth\RevokeAccessToken;
use App\Services\OAuth\RefreshingToken;
use App\Services\OAuth\GetUserFromAccessToken;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends BaseController
{

    /**
     * Summary of loginEmail
     * @param \App\Http\Requests\Api\Auth\LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {

        $email = $request->input('email');
        $rememberMe = $request->input('remember_me', Boolean::FALSE);

        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return $this->setMessage(__('messages.login_fail'))
                ->responseBadRequest();
        }

        try {
            // Create token
            $data = app(PasswordGrantToken::class)->execute([
                'username' => $user->email,
                'password' => $request->input('password'),
                'scope' => '*',
            ]);

            // User Data
            $data['user'] = new UserResource($user);

            return $this->responseSuccess($data)
                ->cookie($this->getRefreskTokenCookie($data['refresh_token']))
                ->cookie($this->getAccessTokenCookie($data['access_token'], $rememberMe))
                ->cookie($this->getRememberMeCookie($rememberMe));

        } catch (Throwable $th) {
            Log::error($th);
        }

        return $this->setMessage(__('messages.login_fail'))
            ->responseBadRequest();
    }

    /**
     * Summary of getRefreskTokenCookie
     * @param mixed $refreshToken
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    private function getRefreskTokenCookie($refreshToken)
    {
        return cookie(
            'refresh_token',
            $refreshToken,
            config('passport.refresh_token_ttl'),
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }

    /**
     * Summary of getAccessTokenCookie
     * @param mixed $accessToken
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    private function getAccessTokenCookie($accessToken, $rememberMe = false)
    {
        return cookie(
            'access_token',
            $accessToken,
            $rememberMe ? config('passport.access_token_ttl') : 0,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }

    /**
     * Summary of getRememberMeCookie
     * @param bool $rememberMe
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    private function getRememberMeCookie($rememberMe)
    {
        return cookie(
            'remember_me',
            $rememberMe ? Boolean::TRUE : Boolean::FALSE,
            config('passport.refresh_token_ttl'),
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }

    /**
     * Summary of logout
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        try {
            $accessToken = $request->input('access_token', $request->cookie('access_token'));

            if (empty($accessToken)) {
                throw new Exception("Logout With Empty Access Token");
            }

            app(RevokeAccessToken::class)->execute([
                'access_token' => $accessToken,
            ]);

        } catch (Throwable $th) {
            Log::error($th);
        }

        return $this->responseSuccess()
            ->cookie('access_token', null, -1, '/', null, true, true, false, 'None')
            ->cookie('refresh_token', null, -1, '/', null, true, true, false, 'None')
            ->cookie('remember_me', null, -1, '/', null, true, true, false, 'None');
    }

    /**
     * Summary of refreshToken
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function refreshToken(Request $request)
    {
        $accessToken = $request->input('access_token', $request->cookie('access_token'));
        $refreshToken = $request->input('refresh_token', $request->cookie('refresh_token'));
        $rememberMe = $request->input('remember_me', $request->cookie('remember_me', Boolean::FALSE));

        if (empty($refreshToken) || (empty($accessToken) && $rememberMe == Boolean::FALSE)) {
            return $this->setMessage(__('messages.refresh_token_fail'))
                ->responseBadRequest();
        }

        try {
            $data = app(RefreshingToken::class)->execute([
                'refresh_token' => $refreshToken,
            ]);

            $user = app(GetUserFromAccessToken::class)->execute([
                'access_token' => $data['access_token'],
            ]);

            $data['user'] = new UserResource($user);

            return $this->responseSuccess($data)
                ->cookie($this->getRefreskTokenCookie($data['refresh_token']))
                ->cookie($this->getAccessTokenCookie($data['access_token'], $rememberMe))
                ->cookie($this->getRememberMeCookie($rememberMe));
            
        } catch (Throwable $th) {
            Log::error($th);
        }

        return $this->setMessage(__('messages.refresh_token_fail'))
            ->responseBadRequest();
    }
}
