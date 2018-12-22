<?php

namespace MichaelDrennen\Robinhood\Responses\Positions;

use MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstruments;

class Positions extends RobinhoodResponseForInstruments {


    /**
     * Positions constructor.
     * @param array $response A parsed response from the Robinhood API
     * @throws \Exception
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->objects[] = new Position( $result );
        endforeach;
    }


    /**
     * When you ask the Robinhood API for your positions, it will return every stock you have ever held. This includes
     * positions that you have sold out of. Use this method to remove positions that don't have shares in them.
     * @return $this
     */
    public function hasShares() {
        $nonZeroPositions = [];
        /**
         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
         */
        foreach ( $this->objects as $position ):
            if ( $position->quantity > 0 ):
                $nonZeroPositions[] = $position;
            endif;
        endforeach;
        $this->objects = $nonZeroPositions;
        return $this;
    }

}