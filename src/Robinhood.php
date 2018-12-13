<?php

namespace MichaelDrennen\Robinhood;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use MichaelDrennen\Robinhood\Responses\Accounts\Accounts;
use MichaelDrennen\Robinhood\Responses\Positions\Positions;

class Robinhood {
    protected $guzzle;

    // Returned from login attempt with Robinhood
    protected $accessToken;
    protected $expiresIn;
    protected $tokenType;
    protected $scope;
    protected $refreshToken;
    protected $mfaCode;
    protected $backupCode;


    public function __construct() {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $this->guzzle = $this->createGuzzleClient();
    }

    /**
     * @param string|NULL $token
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleClient( string $token = NULL ): Client {

        $headers             = [];
        $headers[ 'Accept' ] = 'application/json';
        if ( $token ):
            $headers[ 'Authorization' ] = 'Bearer ' . $token;
        endif;

        $options = [
            'base_uri' => 'https://api.robinhood.com',
            'headers'  => $headers ];
        return new Client( $options );
    }


    /**
     * @param string $username
     * @param string $password
     * @param string $clientId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login( string $username, string $password, string $clientId ) {
        $url = '/oauth2/token/';

        $formParams = [
            'form_params' => [
                'username'   => $username,
                'password'   => $password,
                'grant_type' => 'password',
                'client_id'  => $clientId,
            ],
        ];

        $response = $this->guzzle->request( 'POST', $url, $formParams );

        $body = $response->getBody();

        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        $this->accessToken  = $robinhoodResponse[ 'access_token' ];
        $this->expiresIn    = $robinhoodResponse[ 'expires_in' ];
        $this->tokenType    = $robinhoodResponse[ 'token_type' ];
        $this->scope        = $robinhoodResponse[ 'scope' ];
        $this->refreshToken = $robinhoodResponse[ 'refresh_token' ];
        $this->mfaCode      = $robinhoodResponse[ 'mfa_code' ];
        $this->backupCode   = $robinhoodResponse[ 'backup_code' ];

        $this->guzzle = $this->createGuzzleClient( $this->accessToken );
    }

    /**
     * @return \MichaelDrennen\Robinhood\Responses\Accounts\Accounts
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accounts(): Accounts {
        $url               = '/accounts/';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Accounts( $robinhoodResponse );
    }

    public function positions() {
        $url               = '/positions/';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Positions( $robinhoodResponse );
    }

    public function buy(string $ticker, int $shares){

    }

    public function sellPosition($ticker){

    }
}