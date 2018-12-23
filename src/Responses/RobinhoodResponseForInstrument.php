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
     * Hopefully this is only a temporary function. There was a corporate action where ETP merged with ET. If you held a
     * position with ETP, that would still show up when you call positions(), but will not show up if you ask
     * for quote().
     * @param string $ticker
     * @return string
     */
    protected function translateTicker( string $ticker ): string {
        $ticker = strtoupper( $ticker );
        switch ( $ticker ):
            case 'ETP':
                return 'ET';
        endswitch;
        return $ticker;
    }

    /**
     * The instrument id is available in the instrument field, but there are circumstances where I want the instrument
     * id by itself. This function uses a regular expression to parse it out.
     * Call this from the child's constructor.
     * @param string $instrument Ex: https://api.robinhood.com/instruments/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
     * @return string Ex: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @throws \Exception
     */
    protected function getInstrumentIdFromInstrument( string $instrument ): string {
        $regexPattern = '/.*\/(.*)\/$/';
        preg_match( $regexPattern, $instrument, $matches );
        if ( ! isset( $matches[ 1 ] ) ):
            throw new \Exception( "Unable to find the instrument id from this string: " . $instrument );
        endif;
        return $matches[ 1 ];
    }


    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood Pass this in so I can use the same security token.
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addSymbol( Robinhood $robinhood ) {
        $instrumentId = $this->instrumentId;

        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instrument $instrument
         */
        $instrument   = $robinhood->instrument( $instrumentId );
        $this->symbol = $this->translateTicker( $instrument->symbol );
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