<?php

namespace MichaelDrennen\Robinhood\Responses\Accounts;

class Accounts {
    public $accounts = [];


    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->accounts[] = new Account( $result );
        endforeach;
    }

}