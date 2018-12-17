<?php

namespace MichaelDrennen\Robinhood;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use MichaelDrennen\Robinhood\Responses\Accounts\Accounts;
use MichaelDrennen\Robinhood\Responses\Instruments\Instrument;
use MichaelDrennen\Robinhood\Responses\Instruments\Instruments;
use MichaelDrennen\Robinhood\Responses\Orders\Order;
use MichaelDrennen\Robinhood\Responses\Orders\Orders;
use MichaelDrennen\Robinhood\Responses\Positions\Positions;
use MichaelDrennen\Robinhood\Responses\Quotes\Quote;

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

    protected $accounts;

    public $mainAccountId;
    public $mainAccountUrl; // Needed for orders.


    /**
     * Robinhood constructor.
     */
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

        $response           = $this->guzzle->request( 'POST', $url, $formParams );
        $body               = $response->getBody();
        $robinhoodResponse  = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        $this->accessToken  = $robinhoodResponse[ 'access_token' ];
        $this->expiresIn    = $robinhoodResponse[ 'expires_in' ];
        $this->tokenType    = $robinhoodResponse[ 'token_type' ];
        $this->scope        = $robinhoodResponse[ 'scope' ];
        $this->refreshToken = $robinhoodResponse[ 'refresh_token' ];
        $this->mfaCode      = $robinhoodResponse[ 'mfa_code' ];
        $this->backupCode   = $robinhoodResponse[ 'backup_code' ];
        $this->guzzle       = $this->createGuzzleClient( $this->accessToken );
    }

    /**
     * @param string|NULL $accountId Ex: ABC12345
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function setMainAccountId( string $accountId = NULL ) {
        $this->accounts = $this->accounts();

        if ( is_null( $accountId ) ):
            $this->mainAccountId  = $this->accounts->getMainAccountId();
            $this->mainAccountUrl = $this->accounts->getMainAccountUrl();
        endif;
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

    /**
     * This method will return an array of Position records for every stock this account has ever owned. Even positions
     * that you have sold out of.
     * @todo Add code to handle pagination links when they get returned from Robinhood.
     *       Once the test account has had a large enough number of stocks in it, the Robinhood API will probably send
     *       paginated links to get the rest of your holdings.
     * @return \MichaelDrennen\Robinhood\Responses\Positions\Positions
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function positions() {
        $url               = '/positions/';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Positions( $robinhoodResponse );
    }


    /**
     * @param string $instrumentId An identifier used by Robinhood to identify financial instruments like stocks.
     * @return \MichaelDrennen\Robinhood\Responses\Instruments\Instrument
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function instrument( string $instrumentId ): Instrument {
        $url               = '/instruments/' . $instrumentId;
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Instrument( $robinhoodResponse );
    }


    /**
     * @param string $queryString Ticker, company name, part of a company name or ticker, whatever.
     * @return \MichaelDrennen\Robinhood\Responses\Instruments\Instruments
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function instruments( string $queryString ): Instruments {
        $url               = '/instruments/';
        $response          = $this->guzzle->request( 'GET', $url, [
            'query' => [ 'query' => strtoupper( $queryString ) ],
        ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Instruments( $robinhoodResponse );
    }

    /**
     * Some calls to the Robinhood API will return URLs in the result set. Use this method as a one-off to request that
     * URL and see what gets returned.
     * @param string $url
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function url( string $url ): array {
        $headers             = [];
        $headers[ 'Accept' ] = 'application/json';
        if ( ! $this->accessToken ):
            throw new \Exception( "You need to login and get an access token before you can fire off this function." );
        endif;
        $headers[ 'Authorization' ] = 'Bearer ' . $this->accessToken;
        $options                    = [
            'headers' => $headers,
        ];
        $guzzleClient               = new Client( $options );
        $response                   = $guzzleClient->request( 'GET', $url );
        $body                       = $response->getBody();
        $robinhoodResponse          = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return $robinhoodResponse;
    }



    //          account: _private.account,
    //          instrument: options.instrument.url,
    //          price: options.bid_price,
    //          stop_price: options.stop_price,
    //          quantity: options.quantity,
    //          side: options.transaction,
    //          symbol: options.instrument.symbol.toUpperCase(),
    //          time_in_force: options.time || 'gfd',
    //          trigger: options.trigger || 'immediate',
    //          type: options.type || 'market'
    public function buy( string $account, string $ticker, int $shares ) {


        $url               = '/orders/';
        $response          = $this->guzzle->request( 'POST', $url,
                                                     [
                                                         'form_params' => [
                                                             'account'       => $account,
                                                             'instrument'    => $instrumentUrl,
                                                             'price'         => $bidPrice,
                                                             //
                                                             'quantity'      => $shares,
                                                             'side'          => 'buy',
                                                             'symbol'        => $ticker,
                                                             'time_in_force' => 'gfd',
                                                             'trigger'       => 'immediate',
                                                             'type'          => 'market',
                                                         ],
                                                     ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return $robinhoodResponse;
    }


    /**
     * @return \MichaelDrennen\Robinhood\Responses\Orders\Orders
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getRecentOrders(): Orders {
        $url               = '/orders/';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Orders( $robinhoodResponse );
    }

    /**
     * @param string $orderId Ex: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @return \MichaelDrennen\Robinhood\Responses\Orders\Order
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrderInformation( string $orderId ): Order {
        $url               = '/orders/';
        $response          = $this->guzzle->request( 'GET', $url, [
            'query' => [ 'query' => strtoupper( $orderId ) ],
        ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Order( $robinhoodResponse );
    }

    /**
     * Need to cancel a pending order?
     * @param string $orderId Ex: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @return \MichaelDrennen\Robinhood\Responses\Orders\Order Is this what is really returned?
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelOrder( string $orderId ): Order {
        $url               = '/orders/' . $orderId . '/cancel';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Order( $robinhoodResponse );
    }


    /**
     * @TODO test extendedHours after hours to see if it will execute if passed in a TRUE value.
     * @param string     $accountUrl    Ex: https://api.robinhood.com/accounts/ABC12345/
     * @param string     $ticker        Ex: LODE
     * @param int        $shares        How many shares you want to buy.
     * @param bool       $extendedHours Not sure this is required either...
     * @return \MichaelDrennen\Robinhood\Responses\Orders\Order
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function marketBuy( string $accountUrl, string $ticker, int $shares, bool $extendedHours = FALSE ) {
        $instrumentUrl = $this->getInstrumentUrlFromTicker( $ticker );

        $bidPrice = $this->getBidPriceFromTicker($ticker);
        $adjustedBidPrice = $bidPrice + 1; // Add a dollar to ensure execution.

        $url               = '/orders/';
        $response          = $this->guzzle->request( 'POST', $url,
                                                     [
                                                         'form_params' => [
                                                             'account'        => $accountUrl,
                                                             'instrument'     => $instrumentUrl,
                                                             'price'          => $adjustedBidPrice,
                                                             'quantity'       => $shares,
                                                             'side'           => 'buy',
                                                             'symbol'         => $ticker,
                                                             'time_in_force'  => 'gfd',
                                                             'trigger'        => 'immediate',
                                                             'type'           => 'market',
                                                             'extended_hours' => $extendedHours,
                                                         ],
                                                     ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Order( $robinhoodResponse );
    }

    public function limitBuy( string $account, string $instrumentUrl, string $ticker, int $shares, float $bidPrice = NULL, bool $extendedHours = FALSE ) {
        $url               = '/orders/';
        $response          = $this->guzzle->request( 'POST', $url,
                                                     [
                                                         'form_params' => [
                                                             'account'        => $account,
                                                             'instrument'     => $instrumentUrl,
                                                             'price'          => $bidPrice,
                                                             'quantity'       => $shares,
                                                             'side'           => 'buy',
                                                             'symbol'         => $ticker,
                                                             'time_in_force'  => 'gfd',
                                                             'trigger'        => 'immediate',
                                                             'type'           => 'limit',
                                                             'extended_hours' => $extendedHours,
                                                         ],
                                                     ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Order( $robinhoodResponse );
    }


    protected function marketSell( string $account, string $instrumentUrl, string $ticker, int $shares, float $stopPrice = NULL, bool $extendedHours = FALSE ) {
        $url               = '/orders/';
        $response          = $this->guzzle->request( 'POST', $url,
                                                     [
                                                         'form_params' => [
                                                             'account'        => $account,
                                                             'instrument'     => $instrumentUrl,
                                                             'price'          => $stopPrice,
                                                             'quantity'       => $shares,
                                                             'side'           => 'sell',
                                                             'symbol'         => $ticker,
                                                             'time_in_force'  => 'gfd',
                                                             'trigger'        => 'immediate',
                                                             'type'           => 'market',
                                                             'extended_hours' => $extendedHours,
                                                         ],
                                                     ] );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );
        return new Order( $robinhoodResponse );
    }

    /**
     * @param string $ticker
     * @return \MichaelDrennen\Robinhood\Responses\Quotes\Quote
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function quote(string $ticker): Quote{
        $url               = '/quotes/' . $ticker . '/';
        $response          = $this->guzzle->request( 'GET', $url );
        $body              = $response->getBody();
        $robinhoodResponse = \GuzzleHttp\json_decode( $body->getContents(), TRUE );

        return new Quote( $robinhoodResponse );
    }

    /**
     * @param string $ticker
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception;
     */
    protected function getInstrumentUrlFromTicker( string $ticker ) {
        $instruments = $this->instruments( $ticker );

        /**
         * @var $instrument \MichaelDrennen\Robinhood\Responses\Instruments\Instrument
         */
        foreach ( $instruments->instruments as $instrument ):
            if ( $ticker == $instrument->symbol ):
                return $instrument->url;
            endif;
        endforeach;
        throw new \Exception( "Unable to find the instrument url for the ticker: " . $ticker );
    }

    protected function getBidPriceFromTicker(string $ticker): float{
        $quote = $this->quote($ticker);
        return $quote->bid_price;
    }


}