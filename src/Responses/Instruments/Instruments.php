<?php

namespace MichaelDrennen\Robinhood\Responses\Instruments;

class Instruments {
    public $instruments = [];


    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->instruments[] = new Instrument( $result );
        endforeach;
    }

}