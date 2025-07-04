<?php

namespace App\Services\OAuth;

use App\Services\BaseService;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Throwable;
use Illuminate\Support\Facades\Log;

class GetTokenIdFromToken extends BaseService {
    /**
     * @var Configuration
     */
    private $jwtConfiguration;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string',
        ];
    }
    
    public function execute(array $data) {
        $this->validate($data);

        try {
            $this->initJwtConfiguration();

            // Attempt to parse the JWT
            $token = $this->jwtConfiguration->parser()->parse($data['token']);

            $claims = $token->claims();

            return $claims->get('jti');

        } catch (Throwable $th) {
            Log::error($th);
        }

        return null;
    }

    /**
     * Initialise the JWT configuration.
     */
    private function initJwtConfiguration()
    {
        $this->jwtConfiguration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('empty', 'empty'));
    } 
}