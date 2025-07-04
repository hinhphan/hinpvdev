<?php

namespace App\Services\OAuth;

use App\Services\BaseService;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OAuthGrantToken extends BaseService {
    
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $grantType = '';

    public function __construct() {
        $this->endpoint = route('passport.token');

        if (app()->isLocal()) {
            $this->endpoint = config('passport.passport_local_url');
        }
    }

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
            'scope' => 'nullable',
        ];
    }
    
    public function execute(array $data) {
        $this->validate($data);

        if (empty($this->grantType)) throw new RuntimeException('Require Grant Type');

        $response = Http::asForm()->post($this->endpoint, [
            'grant_type' => $this->grantType,
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $data['username'],
            'password' => $data['password'],
            'scope' => $data['scope'] ?? '',
        ]);

        $response->throw();

        $responseData = $response->json();

        return $responseData;
    }
}