<?php

namespace MichaelDrennen\Robinhood\Responses;


use MichaelDrennen\Robinhood\Robinhood;

class RobinhoodResponseForInstrument extends RobinhoodResponse {

    // Denormalized properties. These contain data that exist in other properties, but I want in a different format.
    public $instrumentId;

    // Related properties. These contain data that can only be retrieved through other API calls.
    public $symbol;
    public $lastTradePrice;


    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood Pass this in so I can use the same security token.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function addSymbol( Robinhood $robinhood ) {
        if ( is_null( $this->instrumentId ) ):
            throw new \Exception( "You need to set the instrumentId before you call addSymbol()" );
        endif;

        $instrumentId = $this->instrumentId;

        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instrument $instrument
         */
        $instrument   = $robinhood->instrument( $instrumentId );
        $this->symbol = Robinhood::translateTicker( $instrument->symbol );
    }

    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addLastTradePrice( Robinhood $robinhood ) {

        if ( ! isset( $this->symbol ) ):
            throw new \Exception( "You need to call addSymbol() before you call addLastTradePrice()" );
        endif;

        try {
            /**
             * @var \MichaelDrennen\Robinhood\Responses\Quotes\Quote $quote
             */
            $quote                = $robinhood->quote( $this->symbol );
            $this->lastTradePrice = $quote->last_trade_price;
        } catch ( \Exception $exception ) {
            $this->lastTradePrice = NULL;
        }

    }
}