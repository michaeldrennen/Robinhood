<?php

namespace MichaelDrennen\Robinhood\Responses\Accounts;

use Carbon\Carbon;


class Account {
    public $rhs_account_number; // 234567898
    public $deactivated; // bool?
    public $updated_at; // 2018-07-15T11:01:01.111112Z
    public $margin_balances;
    public $portfolio; // https://api.robinhood.com/accounts/6UC62541/portfolio/
    public $cash_balances; // ?
    public $can_downgrade_to_cash; // https://api.robinhood.com/accounts/6UC62541/can_downgrade_to_cash/
    public $withdrawal_halted; // bool?
    public $cash_available_for_withdrawal; // 115.2700
    public $type; // margin
    public $sma; // 1154.6500
    public $sweep_enabled; // bool?
    public $deposit_halted; // bool?
    public $buying_power; // 1154.6500
    public $user; // https://api.robinhood.com/user/
    public $max_ach_early_access_amount; // 1000.00
    public $option_level; // option_level_2
    public $instant_eligibility;
    public $cash_held_for_orders; // 0.0000
    public $only_position_closing_trades; // ?
    public $url; // https://api.robinhood.com/accounts/6UC62541/
    public $positions; // https://api.robinhood.com/accounts/6UC62541/positions/
    public $created_at; // 2018-06-26T15:24:32.463191Z
    public $cash; // 115.2700
    public $sma_held_for_orders; // 0.0000
    public $unsettled_debit; // 0.0000
    public $account_number; // 6UC62541
    public $is_pinnacle_account; // 1
    public $uncleared_deposits; // 0.0000
    public $unsettled_funds; // 0.0000


    public function __construct( array $result ) {
        $this->rhs_account_number            = (string)$result[ 'rhs_account_number' ];
        $this->deactivated                   = empty( $result[ 'deactivated' ] ) ? FALSE : TRUE;
        $this->updated_at                    = Carbon::parse( $result[ 'updated_at' ] );
        $this->margin_balances               = new MarginBalances( $result[ 'margin_balances' ] );
        $this->portfolio                     = (string)$result[ 'portfolio' ];
        $this->cash_balances                 = $result[ 'cash_balances' ];
        $this->can_downgrade_to_cash         = (string)$result[ 'can_downgrade_to_cash' ];
        $this->withdrawal_halted             = empty( $result[ 'withdrawal_halted' ] ) ? FALSE : TRUE;
        $this->cash_available_for_withdrawal = (float)$result[ 'cash_available_for_withdrawal' ];
        $this->type                          = (string)$result[ 'type' ];
        $this->sma                           = (float)$result[ 'sma' ];
        $this->sweep_enabled                 = empty( $result[ 'sweep_enabled' ] ) ? FALSE : TRUE;
        $this->deposit_halted                = empty( $result[ 'deposit_halted' ] ) ? FALSE : TRUE;
        $this->buying_power                  = (float)$result[ 'buying_power' ];
        $this->user                          = (string)$result[ 'user' ];
        $this->max_ach_early_access_amount   = (float)$result[ 'max_ach_early_access_amount' ];
        $this->option_level                  = (string)$result[ 'option_level' ];
        $this->instant_eligibility           = new InstantEligibility( $result[ 'instant_eligibility' ] );
        $this->cash_held_for_orders          = (float)$result[ 'cash_held_for_orders' ];
        $this->only_position_closing_trades  = (string)$result[ 'only_position_closing_trades' ];
        $this->url                           = (string)$result[ 'url' ];
        $this->positions                     = (string)$result[ 'positions' ];
        $this->created_at                    = Carbon::parse( $result[ 'created_at' ] );
        $this->cash                          = (float)$result[ 'cash' ];
        $this->sma_held_for_orders           = (float)$result[ 'sma_held_for_orders' ];
        $this->unsettled_debit               = (float)$result[ 'unsettled_debit' ];
        $this->account_number                = (string)$result[ 'account_number' ];
        $this->is_pinnacle_account           = empty( $result[ 'is_pinnacle_account' ] ) ? FALSE : TRUE;
        $this->uncleared_deposits            = (float)$result[ 'uncleared_deposits' ];
        $this->unsettled_funds               = (float)$result[ 'unsettled_funds' ];

    }


}