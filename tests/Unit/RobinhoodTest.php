<?php

namespace Tests\Unit;

use Dotenv\Dotenv;
use GuzzleHttp\Exception\ClientException;
use MichaelDrennen\Robinhood\Responses\Positions\Position;
use MichaelDrennen\Robinhood\Robinhood;
use PHPUnit\Framework\TestCase;

class RobinhoodTest extends TestCase {

    /**
     * @test
     */
    public function badLoginShouldThrowException() {
        $this->expectException( ClientException::class );
        $robinhood = new Robinhood();
        $robinhood->login( 'foo', 'bar' );
    }

    /**
     * @test
     */
    public function callToUrlWhenNotLoggedInShouldThrowException() {
        $this->expectException( \Exception::class );
        $robinhood = new Robinhood();
        $results   = $robinhood->url( 'https://api.robinhood.com/fundamentals/MSFT/' );
    }


    /**
     * @test
     */
    public function validLoginShouldGrantAccessToken(): Robinhood {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $accessToken  = $robinhood->getAccessToken();
        $refreshToken = $robinhood->getRefreshToken();
        $this->assertNotEmpty( $accessToken );
        $this->assertNotEmpty( $refreshToken );
        return $robinhood;
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function setMainAccountShouldSetAnId( Robinhood $robinhood ) {
        $robinhood->setMainAccountId();
        $mainAccountId  = $robinhood->mainAccountId;
        $mainAccountUrl = $robinhood->mainAccountUrl;
        $this->assertNotEmpty( $mainAccountId );
        $this->assertNotEmpty( $mainAccountUrl );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getPositionsShouldReturnAnArray( Robinhood $robinhood ) {
        $positions = $robinhood->positions()
                               ->addSymbols( $robinhood )
                               ->addLastTradePrices( $robinhood )
                               ->addMarketValueFromLastTradePrices( $robinhood );

        $this->assertNotEmpty( $positions );

        /**
         * @var Position $position
         */
        foreach ( $positions->objects as $position ):
            $this->assertNotEmpty( $position->symbol );
        endforeach;
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getInstrumentsBySymbolShouldNotBeEmpty( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instruments $instruments
         */
        $instruments = $robinhood->instrumentsBySymbol( 'LODE' );
        $this->assertNotEmpty( $instruments->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getInstrumentsByQueryStringShouldNotBeEmpty( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instruments $instruments
         */
        $instruments = $robinhood->instrumentsByQueryString( 'finance' );
        $this->assertNotEmpty( $instruments->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getUrlShouldNotBeEmpty( Robinhood $robinhood ) {
        $results = $robinhood->url( 'https://api.robinhood.com/fundamentals/MSFT/' );
        $this->assertNotEmpty( $results );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getRecentOrdersShouldNotBeEmpty( Robinhood $robinhood ) {
        $recentOrdres = $robinhood->getRecentOrders();
        $this->assertNotEmpty( $recentOrdres->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function marketBuyShouldPlaceOrder( Robinhood $robinhood ) {
        $order = $robinhood->marketBuy( $robinhood->mainAccountUrl, 'LODE', 1, FALSE );
        $this->assertNotEmpty( $order->id );
        return $order->id;
    }

    /**
     * @test
     * @depends marketBuyShouldPlaceOrder
     */
    public function getOrderInformationShouldNotBeEmpty( string $orderId ) {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $order = $robinhood->getOrderInformation( $orderId );
        $this->assertNotEmpty( $order->id );
        return $orderId;
    }


    /**
     * @test
     * @depends marketBuyShouldPlaceOrder
     */
    public function cancelOrderShouldNotBeEmpty( string $orderId ) {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $order = $robinhood->cancelOrder( $orderId );
        $this->assertNotEmpty( $order->id );
    }




    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login() {


//        $accounts = $robinhood->accounts();
//        print_r( $accounts );
//
//        $positionsWithShares = $robinhood->positions()->hasShares();
//
//
//        /**
//         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
//         */
//        foreach ( $positionsWithShares->positions as $i => $position ):
//            $instrument = $robinhood->instrument( $position->instrumentId );
//            print_r( $instrument );
//        endforeach;
//
//        print( count( $positionsWithShares->positions ) . " with shares" );
//
//        $robinhood->instruments( 'lode' );

    }

//    public function testInstruments(){
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
//        $instruments = $robinhood->instruments('LODE');
//        print_r($instruments);
//    }
//
//    public function testGetRecentOrders(){
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
//        $orders = $robinhood->getRecentOrders();
//        print_r($orders);
//    }

//    public function testUnexecutedOrders(){
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
//        $unexecutedOrders = $robinhood->getRecentOrders()->pendingOrders();
//        print_r($unexecutedOrders);
//    }


//    public function testMarketBuyWithAdjustedBidPrice() {
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
//        $robinhood->setMainAccountId();
//        $order = $robinhood->marketBuy( $robinhood->mainAccountUrl, 'LODE', 1 );
//        print_r( $order );
//
//    }


//    public function testMarketSellWithAdjustedAskPrice() {
//        $dotenv = new Dotenv( __DIR__ );
//        $dotenv->load();
//        $robinhood = new Robinhood();
//
//        try {
//            $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
//            $robinhood->setMainAccountId();
//            $order = $robinhood->marketSell( $robinhood->mainAccountUrl, 'LODE', 1 );
//            print_r( $order );
//        } catch ( \Exception $exception ) {
//            print_r( $exception->getMessage() );
//        }
//
//
//    }


//    public function Buy() {
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
//        $accounts = $robinhood->accounts();
//        print_r( $accounts );
//
//        $positionsWithShares = $robinhood->positions()->hasShares();
//
//
//        $stocks = [];
//        /**
//         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
//         */
//        foreach ( $positionsWithShares->positions as $position ):
//            $instrumentId = $position->instrumentId;
//            $instrument   = $robinhood->instrument( $instrumentId );
//            echo "\nInstrument: " . $instrument->symbol;
//            $stocks[ $instrument->symbol ] = [
//                'symbol'        => $instrument->symbol,
//                'instrumentUrl' => $instrument->url,
//            ];
//        endforeach;
//
//        //print_r($positionsWithShares);
//
//        /**
//         * @var $mainAccount \MichaelDrennen\Robinhood\Responses\Accounts\Account
//         */
//        $mainAccount   = $accounts->accounts[ 0 ];
//        $accountNumber = $mainAccount->rhs_account_number;
//        $accountUrl    = $mainAccount->url;
//
//        print_r( $mainAccount );
//
//        print_r( $accountNumber );
//        print_r( $stocks );
//
//        $response = $robinhood->buy($accountUrl,$stocks['LODE']['instrumentUrl'],'LODE',1,0.16,0.16);
//        $response = $robinhood->buy($accountNumber,'https://robinhood.com/stocks/LODE','LODE',1);
//
//        print_r($response);
//
//    }
}