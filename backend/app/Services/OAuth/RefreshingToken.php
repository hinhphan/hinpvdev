<?php

namespace App\Services\OAuth;

use App\Services\BaseService;
use Illuminate\Support\Facades\Http;

class RefreshingToken extends BaseService {
    /**
     * @var string
     */
    protected $endpoint;

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
            'refresh_token' => 'required|string',
        ];
    }
    
    public function execute(array $data) {
        $this->validate($data);

        $response = Http::asForm()->post($this->endpoint, [
            'grant_type' => 'refresh_token',
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'refresh_token' => $data['refresh_token'],
            'scope' => '',
        ]);

        $response->throw();

        $responseData = $response->json();

        return $responseData;
    }
}