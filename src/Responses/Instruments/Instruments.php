<?php

namespace MichaelDrennen\Robinhood\Responses\Instruments;

class Instruments {
    public $instruments = [];


    public function __construct( array $robinhoodResponse ) {
        foreach ( $robinhoodResponse[ 'results' ] as $result ):
            $this->instruments[] = new Instrument( $result );
        endforeach;
    }

}