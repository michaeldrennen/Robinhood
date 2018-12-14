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

        $positions = $robinhood->positions();
        //print_r( $positions );

        /**
         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
         */
//        foreach ( $positions->positions as $i => $position ):
//            try {
//                $response = $robinhood->url( $position->instrument );
//                echo "\n\n" . $i . " of " . count( $positions->positions ) . "\n\n";
//                print_r( $response );
//            } catch ( \Exception $exception ) {
//                echo "\n\n" . $exception->getMessage() . "\n\n";
//            }
//        endforeach;

        //$robinhood->instruments('lode');

    }
}