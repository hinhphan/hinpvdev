<?php

namespace App\Services\OAuth;

class PasswordGrantToken extends OAuthGrantToken {
    
    public function execute(array $data) {
        $this->grantType = 'password';

        return parent::execute($data);
    }
}