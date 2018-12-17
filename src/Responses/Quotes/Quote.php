<?php

namespace MichaelDrennen\Robinhood\Responses\Quotes;

use Carbon\Carbon;

class Quote {

    public $ask_price; // 54.2100,
    public $ask_size; // 2000,
    public $bid_price; // 54.2000,
    public $bid_size; // 1800,
    public $last_trade_price; // 54.1900,
    public $last_extended_hours_trade_price; // null,
    public $previous_close; // 54.6600,
    public $adjusted_previous_close; // 54.6600,
    public $previous_close_date; // 2016-03-17,
    public $symbol; // MSFT,
    public $trading_halted; // false,
    public $updated_at; // 2016-03-18T15:45:28Z


    public function __construct( array $quote ) {
        $this->ask_price                       = (float)$quote[ 'ask_price' ];
        $this->ask_size                        = (int)$quote[ 'ask_size' ];
        $this->bid_price                       = (float)$quote[ 'bid_price' ];
        $this->bid_size                        = (int)$quote[ 'bid_size' ];
        $this->last_trade_price                = (float)$quote[ 'last_trade_price' ];
        $this->last_extended_hours_trade_price = $quote[ 'last_extended_hours_trade_price' ];
        $this->previous_close                  = (float)$quote[ 'previous_close' ];
        $this->adjusted_previous_close         = (float)$quote[ 'adjusted_previous_close' ];
        $this->previous_close_date             = Carbon::parse($quote[ 'previous_close_date' ]);
        $this->symbol                          = (string)$quote[ 'symbol' ];
        $this->trading_halted                  = (bool)$quote[ 'trading_halted' ];
        $this->updated_at                      = Carbon::parse($quote[ 'updated_at' ]);
    }


}