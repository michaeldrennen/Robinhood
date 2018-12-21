<?php

namespace MichaelDrennen\Robinhood\Responses\Orders;

use Carbon\Carbon;
use MichaelDrennen\Robinhood\Robinhood;

class Order {

    public $updated_at; // 2018-12-14T21:23:19.586667Z
    public $ref_id; //
    public $time_in_force; // gfd
    public $fees; // 0.00
    public $cancel; // https://api.robinhood.com/orders/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/cancel/
    public $response_category; //
    public $id; // xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
    public $cumulative_quantity; // 0.00000
    public $stop_price; //
    public $reject_reason; //
    public $instrument; // https://api.robinhood.com/instruments/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
    public $state; // unconfirmed
    public $trigger; // immediate
    public $override_dtbp_checks; //
    public $type; // market
    public $last_transaction_at; // 2018-12-14T21:23:19.576086Z
    public $price; // 0.16000000
    public $executions = []; // Array
    public $extended_hours; //
    public $account; // https://api.robinhood.com/accounts/5UB71626/
    public $url; // https://api.robinhood.com/orders/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
    public $created_at; // 2018-12-14T21:23:19.576086Z
    public $side; // buy
    public $override_day_trade_checks; //
    public $position; // https://api.robinhood.com/positions/6UB12345/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
    public $average_price; //
    public $quantity; // 1.00000

    //
    public $symbol;

    public function __construct( $result ) {
        $this->updated_at                = Carbon::parse( $result[ 'updated_at' ] );
        $this->ref_id                    = $result[ 'ref_id' ];
        $this->time_in_force             = (string)$result[ 'time_in_force' ];
        $this->fees                      = (float)$result[ 'fees' ];
        $this->cancel                    = (string)$result[ 'cancel' ];
        $this->response_category         = $result[ 'response_category' ];
        $this->id                        = (string)$result[ 'id' ];
        $this->cumulative_quantity       = (float)$result[ 'cumulative_quantity' ];
        $this->stop_price                = $result[ 'stop_price' ];
        $this->reject_reason             = $result[ 'reject_reason' ];
        $this->instrument                = (string)$result[ 'instrument' ];
        $this->state                     = (string)$result[ 'state' ];
        $this->trigger                   = (string)$result[ 'trigger' ];
        $this->override_dtbp_checks      = $result[ 'override_dtbp_checks' ];
        $this->type                      = (string)$result[ 'type' ];
        $this->last_transaction_at       = Carbon::parse( $result[ 'last_transaction_at' ] );
        $this->price                     = (float)$result[ 'price' ];
        $this->executions                = new Executions( $result[ 'executions' ] );
        $this->extended_hours            = $result[ 'extended_hours' ];
        $this->account                   = (string)$result[ 'account' ];
        $this->url                       = (string)$result[ 'url' ];
        $this->created_at                = Carbon::parse( $result[ 'created_at' ] );
        $this->side                      = (string)$result[ 'side' ];
        $this->override_day_trade_checks = $result[ 'override_day_trade_checks' ];
        $this->position                  = (string)$result[ 'position' ];
        $this->average_price             = $result[ 'average_price' ];
        $this->quantity                  = (float)$result[ 'quantity' ];
    }


    /**
     * @param \MichaelDrennen\Robinhood\Robinhood $robinhood
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addSymbol( Robinhood $robinhood ) {
        $instrumentId = $this->id;
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instrument $instrument
         */
        try {
            $instrument   = $robinhood->instrument( $instrumentId );
            $this->symbol = $instrument->symbol;
        } catch ( \Exception $exception ) {
            $this->symbol = NULL;
            throw $exception;
        }

    }

}