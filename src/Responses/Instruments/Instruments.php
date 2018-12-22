<?php

namespace MichaelDrennen\Robinhood\Responses\Instruments;

use MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstruments;

class Instruments extends RobinhoodResponseForInstruments {


    /**
     * Instruments constructor.
     * @param array $robinhoodResponse
     */
    public function __construct( array $robinhoodResponse ) {
        foreach ( $robinhoodResponse[ 'results' ] as $result ):
            $this->objects[] = new Instrument( $result );
        endforeach;
    }

}