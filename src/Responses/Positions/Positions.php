<?php

namespace MichaelDrennen\Robinhood\Responses\Positions;

class Positions {

    /**
     * @var array An array of Position objects.
     */
    public $positions = [];


    /**
     * Positions constructor.
     * @param array $response
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->positions[] = new Position( $result );
        endforeach;
    }

}