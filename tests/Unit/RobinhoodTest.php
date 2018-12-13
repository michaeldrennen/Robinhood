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


    public function testLogin() {
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ), getenv( 'CLIENT_ID' ) );
        $accounts = $robinhood->accounts();
        print_r( $accounts );

        $positions = $robinhood->positions();
        print_r( $positions );
    }
}