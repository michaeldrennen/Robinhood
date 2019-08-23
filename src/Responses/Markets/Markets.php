<?php

namespace MichaelDrennen\Robinhood\Responses\Markets;

class Markets {

    /**
     * @var array An array of Quote objects.
     */
    public $markets = [];


    /**
     * Quotes constructor.
     * @param array $response A parsed response from the Robinhood API
     * @throws \Exception
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->markets[] = new Market( $result );
        endforeach;
    }
}