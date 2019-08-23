<?php

namespace MichaelDrennen\Robinhood\Responses;


use MichaelDrennen\Robinhood\Robinhood;

class RobinhoodResponseForInstruments extends RobinhoodResponse {


    /**
     * @var array An array of objects that extend the RobinhoodResponseForInstrument class.
     */
    public $objects = [];

    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood
     * @return $this
     */
    public function addSymbols( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstrument $object
         */
        foreach ( $this->objects as $i => $object ):
            $this->objects[ $i ]->addSymbol( $robinhood );
        endforeach;
        return $this;
    }



    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood
     * @return $this
     */
    public function addLastTradePrices( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstrument $object
         */
        foreach ( $this->objects as $i => $object ):
            $this->objects[ $i ]->addLastTradePrice( $robinhood );
        endforeach;
        return $this;
    }



}