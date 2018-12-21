<?php

namespace MichaelDrennen\Robinhood\Responses\Positions;

use MichaelDrennen\Robinhood\Robinhood;

class Positions {

    /**
     * @var array An array of Position objects.
     */
    public $positions = [];


    /**
     * Positions constructor.
     * @param array $response A parsed response from the Robinhood API
     * @throws \Exception
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->positions[] = new Position( $result );
        endforeach;
    }


    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood
     * @return $this
     */
    public function addSymbols(Robinhood $robinhood){
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Positions\Position $position
         */
        foreach($this->positions as $i => $position):
            $this->positions[$i]->addSymbol($robinhood);
        endforeach;
        return $this;
    }

    /**
     * When you ask the Robinhood API for your positions, it will return every stock you have ever held. This includes
     * positions that you have sold out of. Use this method to remove positions that don't have shares in them.
     * @return $this
     */
    public function hasShares(){
        $nonZeroPositions = [];
        /**
         * @var $position \MichaelDrennen\Robinhood\Responses\Positions\Position
         */
        foreach ( $this->positions as $position ):
            if ( $position->quantity > 0 ):
                $nonZeroPositions[] = $position;
            endif;
        endforeach;
        $this->positions = $nonZeroPositions;
        return $this;
    }

}