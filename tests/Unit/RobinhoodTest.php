<?php

namespace Tests\Unit;

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
    public function testLogin() {
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
        $accounts = $robinhood->accounts();
        print_r( $accounts );

        $positionsWithShares = $robinhood->positions()->hasShares();


        /**
         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
         */
        foreach ( $positionsWithShares->positions as $i => $position ):
            $instrument = $robinhood->instrument( $position->instrumentId );
            print_r( $instrument );
        endforeach;

        print( count( $positionsWithShares->positions ) . " with shares" );

        //$robinhood->instruments('lode');

    }
}