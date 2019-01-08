<?php

namespace MichaelDrennen\Robinhood\Responses\Quotes;

class Quotes {

    /**
     * @var array An array of Quote objects.
     */
    public $quotes = [];


    /**
     * Quotes constructor.
     * @param array $response A parsed response from the Robinhood API
     * @throws \Exception
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->quotes[] = new Quote( $result );
        endforeach;
    }
}