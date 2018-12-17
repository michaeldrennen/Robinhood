<?php

namespace MichaelDrennen\Robinhood\Responses\Orders;

use Carbon\Carbon;

class Execution {

    public $timestamp; // 2018-08-23T16:44:27.592000Z
    public $price; // 13.71000000
    public $settlement_date; // 2018-08-27
    public $id; // xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
    public $quantity; // 10.00000


    public function __construct( $result ) {
        $this->timestamp       = Carbon::parse( $result[ 'timestamp' ] );
        $this->price           = $result[ 'price' ];
        $this->settlement_date = Carbon::parse( $result[ 'settlement_date' ] );
        $this->id              = $result[ 'id' ];
        $this->quantity        = $result[ 'quantity' ];
    }
}