<?php

namespace App\Traits;

use App\Enums\Boolean;

trait TokenCookie {
    /**
     * Summary of getRefreskTokenCookie
     * @param mixed $refreshToken
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function getRefreskTokenCookie($refreshToken)
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
    public function getAccessTokenCookie($accessToken, $rememberMe = false)
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
    public function getRememberMeCookie($rememberMe)
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
     * Summary of getClearAccessTokenCookies
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function getClearAccessTokenCookies()
    {
        return cookie(
            'access_token',
            null,
            -1,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }

    /**
     * Summary of getClearRefreshTokenCookies
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function getClearRefreshTokenCookies()
    {
        return cookie(
            'refresh_token',
            null,
            -1,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }

    /**
     * Summary of getClearRememberMeCookies
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function getClearRememberMeCookies()
    {
        return cookie(
            'remember_me',
            null,
            -1,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }
}