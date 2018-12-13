<?php

namespace MichaelDrennen\Robinhood\Responses\Accounts;

use Carbon\Carbon;

class MarginBalances {

    public $updated_at; // 2018-12-12T08:07:25.300571Z
    public $gold_equity_requirement; // 0.0000
    public $outstanding_interest; // 0.0000
    public $cash_held_for_options_collateral; // 0.0000
    public $uncleared_nummus_deposits; // 0.0000
    public $overnight_ratio; // 1.00
    public $day_trade_buying_power; // 115.2700
    public $cash_available_for_withdrawal; // 115.2700
    public $sma; // 1174.4414
    public $cash_held_for_nummus_restrictions; // 0.0000
    public $marked_pattern_day_trader_date; // ? assume timestamp
    public $unallocated_margin_cash; // 115.2700
    public $start_of_day_dtbp; // 1647.3100
    public $overnight_buying_power_held_for_orders; // 0.0000
    public $day_trade_ratio; // 0.25
    public $cash_held_for_orders; // 0.0000
    public $unsettled_debit; // 0.0000
    public $created_at; // 2018-06-26T17:21:35.168611Z
    public $cash_held_for_dividends; // 0.0000
    public $cash; // 115.2700
    public $start_of_day_overnight_buying_power; // 1154.6500
    public $margin_limit; // 0.0000
    public $overnight_buying_power; // 115.2700
    public $uncleared_deposits; // 0.0000
    public $unsettled_funds; // 0.0000
    public $day_trade_buying_power_held_for_orders; // 0.0000


    public function __construct( array $marginBalances ) {
        $this->updated_at                             = Carbon::parse( $marginBalances[ 'updated_at' ] );
        $this->gold_equity_requirement                = (float)$marginBalances[ 'gold_equity_requirement' ];
        $this->outstanding_interest                   = (float)$marginBalances[ 'outstanding_interest' ];
        $this->cash_held_for_options_collateral       = (float)$marginBalances[ 'cash_held_for_options_collateral' ];
        $this->uncleared_nummus_deposits              = (float)$marginBalances[ 'uncleared_nummus_deposits' ];
        $this->overnight_ratio                        = (float)$marginBalances[ 'overnight_ratio' ];
        $this->day_trade_buying_power                 = (float)$marginBalances[ 'day_trade_buying_power' ];
        $this->cash_available_for_withdrawal          = (float)$marginBalances[ 'cash_available_for_withdrawal' ];
        $this->sma                                    = (float)$marginBalances[ 'sma' ];
        $this->cash_held_for_nummus_restrictions      = (float)$marginBalances[ 'cash_held_for_nummus_restrictions' ];
        $this->marked_pattern_day_trader_date         = Carbon::parse( $marginBalances[ 'marked_pattern_day_trader_date' ] );
        $this->unallocated_margin_cash                = (float)$marginBalances[ 'unallocated_margin_cash' ];
        $this->start_of_day_dtbp                      = (float)$marginBalances[ 'start_of_day_dtbp' ];
        $this->overnight_buying_power_held_for_orders = (float)$marginBalances[ 'overnight_buying_power_held_for_orders' ];
        $this->day_trade_ratio                        = (float)$marginBalances[ 'day_trade_ratio' ];
        $this->cash_held_for_orders                   = (float)$marginBalances[ 'cash_held_for_orders' ];
        $this->unsettled_debit                        = (float)$marginBalances[ 'unsettled_debit' ];
        $this->created_at                             = Carbon::parse( $marginBalances[ 'created_at' ] );
        $this->cash_held_for_dividends                = (float)$marginBalances[ 'cash_held_for_dividends' ];
        $this->cash                                   = (float)$marginBalances[ 'cash' ];
        $this->start_of_day_overnight_buying_power    = (float)$marginBalances[ 'start_of_day_overnight_buying_power' ];
        $this->margin_limit                           = (float)$marginBalances[ 'margin_limit' ];
        $this->overnight_buying_power                 = (float)$marginBalances[ 'overnight_buying_power' ];
        $this->uncleared_deposits                     = (float)$marginBalances[ 'uncleared_deposits' ];
        $this->unsettled_funds                        = (float)$marginBalances[ 'unsettled_funds' ];
        $this->day_trade_buying_power_held_for_orders = (float)$marginBalances[ 'day_trade_buying_power_held_for_orders' ];
    }
}