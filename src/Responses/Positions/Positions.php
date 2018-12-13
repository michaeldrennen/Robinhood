<?php

namespace MichaelDrennen\Robinhood\Responses\Positions;

class Positions {
    public $positions = [];


    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->positions[] = new Position( $result );
        endforeach;
    }

}