<?php

namespace Tests\Unit;

use Dotenv\Dotenv;
use MichaelDrennen\Robinhood\Responses\Instruments\Instrument;
use MichaelDrennen\Robinhood\Robinhood;
use PHPUnit\Framework\TestCase;

class RobinhoodTest extends TestCase {
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest() {


        $this->assertTrue( TRUE );
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    //  public function testLogin() {
//        $robinhood = new Robinhood();
//        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
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

    //$robinhood->instruments('lode');

    // }

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


    public function testMarketSellWithAdjustedAskPrice() {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();

        try {
            $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
            $robinhood->setMainAccountId();
            $order = $robinhood->marketSell( $robinhood->mainAccountUrl, 'LODE', 1 );
            print_r( $order );
        } catch ( \Exception $exception ) {
            print_r( $exception->getMessage() );
        }


    }


    public function Buy() {
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $accounts = $robinhood->accounts();
        print_r( $accounts );

        $positionsWithShares = $robinhood->positions()->hasShares();


        $stocks = [];
        /**
         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
         */
        foreach ( $positionsWithShares->positions as $position ):
            $instrumentId = $position->instrumentId;
            $instrument   = $robinhood->instrument( $instrumentId );
            echo "\nInstrument: " . $instrument->symbol;
            $stocks[ $instrument->symbol ] = [
                'symbol'        => $instrument->symbol,
                'instrumentUrl' => $instrument->url,
            ];
        endforeach;

        //print_r($positionsWithShares);

        /**
         * @var $mainAccount \MichaelDrennen\Robinhood\Responses\Accounts\Account
         */
        $mainAccount   = $accounts->accounts[ 0 ];
        $accountNumber = $mainAccount->rhs_account_number;
        $accountUrl    = $mainAccount->url;

        print_r( $mainAccount );

        print_r( $accountNumber );
        print_r( $stocks );

        //$response = $robinhood->buy($accountUrl,$stocks['LODE']['instrumentUrl'],'LODE',1,0.16,0.16);
//        $response = $robinhood->buy($accountNumber,'https://robinhood.com/stocks/LODE','LODE',1);

        //print_r($response);

    }
}