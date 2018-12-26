<?php

namespace MichaelDrennen\Robinhood\Responses\Positions;

use Carbon\Carbon;
use MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstrument;
use MichaelDrennen\Robinhood\Robinhood;

class Position extends RobinhoodResponseForInstrument {

    public $shares_held_for_stock_grants; // 0.0000
    public $account; // https://api.robinhood.com/accounts/XXXXXXXX/
    public $pending_average_buy_price; // 79.4000
    public $shares_held_for_options_events; // 0.0000
    public $intraday_average_buy_price; // 0.0000
    public $url; // https://api.robinhood.com/positions/XXXXXXXX/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
    public $shares_held_for_options_collateral; // 0.0000
    public $created_at; // 2018-10-26T20:18:53.857034Z
    public $updated_at; // 2018-10-26T20:18:55.260166Z
    public $shares_held_for_buys; // 0.0000
    public $average_buy_price; // 79.4000

    /**
     * @var string The url to the
     */
    public $instrument; // https://api.robinhood.com/instruments/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
    public $intraday_quantity; // 0.0000
    public $shares_held_for_sells; // 0.0000
    public $shares_pending_from_options_events; // 0.0000
    public $quantity; // 1.0000


    // Related properties. These contain data that can only be retrieved through other API calls.
    public $marketValueFromLastTradePrice;
    public $costBasis;


    /**
     * Position constructor.
     * @param array $position
     * @throws \Exception
     */
    public function __construct( array $position ) {
        $this->shares_held_for_stock_grants       = (float)$position[ 'shares_held_for_stock_grants' ];
        $this->account                            = (string)$position[ 'account' ];
        $this->pending_average_buy_price          = (float)$position[ 'pending_average_buy_price' ];
        $this->shares_held_for_options_events     = (float)$position[ 'shares_held_for_options_events' ];
        $this->intraday_average_buy_price         = (float)$position[ 'intraday_average_buy_price' ];
        $this->url                                = (string)$position[ 'url' ];
        $this->shares_held_for_options_collateral = (float)$position[ 'shares_held_for_options_collateral' ];
        $this->created_at                         = Carbon::parse( $position[ 'created_at' ] );
        $this->updated_at                         = Carbon::parse( $position[ 'updated_at' ] );
        $this->shares_held_for_buys               = (float)$position[ 'shares_held_for_buys' ];
        $this->average_buy_price                  = (float)$position[ 'average_buy_price' ];
        $this->instrument                         = (string)$position[ 'instrument' ];
        $this->intraday_quantity                  = (float)$position[ 'intraday_quantity' ];
        $this->shares_held_for_sells              = (float)$position[ 'shares_held_for_sells' ];
        $this->shares_pending_from_options_events = (float)$position[ 'shares_pending_from_options_events' ];
        $this->quantity                           = (float)$position[ 'quantity' ];

        // Assign denormalized properties here.
        $this->instrumentId = $this->getInstrumentIdFromInstrument( $this->instrument );
    }


    /**
     * @throws \Exception
     */
    public function addMarketValueFromLastTradePrice() {
        if ( ! isset( $this->lastTradePrice ) ):
            throw new \Exception( "You need to call addLastTradePrice() first before you calculate the market value." );
        endif;
        $this->marketValueFromLastTradePrice = $this->lastTradePrice * $this->quantity;
    }

    public function addCostBasis() {
        $this->costBasis = (float)( $this->average_buy_price * $this->quantity );
    }

    /**
     * @return bool True if this position has at least one share.
     */
    public function hasShares(): bool {
        if ( $this->quantity > 0 ):
            return TRUE;
        endif;
        return FALSE;
    }

}