<?php

namespace MichaelDrennen\Robinhood\Responses\Instruments;

use Carbon\Carbon;
use MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstrument;

/**
 * Class Instrument
 * @package MichaelDrennen\Robinhood\Responses\Instruments
 */
class Instrument extends RobinhoodResponseForInstrument {

    public $margin_initial_ratio; // 0.8000
    public $rhs_tradability; // tradable
    public $id; // 510a1f2e-8cde-468f-957c-99ae4f528ea6
    public $market; // https://api.robinhood.com/markets/XNAS/
    public $simple_name; // MadrigalPharmaceuticals
    public $min_tick_size; // 
    public $maintenance_ratio; // 0.7500
    public $tradability; // tradable
    public $state; // active
    public $type; // stock
    public $tradeable; // 1
    public $fundamentals; // https://api.robinhood.com/fundamentals/MDGL/
    public $quote; // https://api.robinhood.com/quotes/MDGL/
    public $symbol; // MDGL
    public $day_trade_ratio; // 0.2500
    public $name; // MadrigalPharmaceuticals
    public $tradable_chain_id; // 8d76e826-c232-4ed2-bf89-7bd844135ebd
    public $splits; // https://api.robinhood.com/instruments/510a1f2e-8cde-468f-957c-99ae4f528ea6/splits/
    public $url; //  https://api.robinhood.com/instruments/510a1f2e-8cde-468f-957c-99ae4f528ea6/
    public $country; // US
    public $bloomberg_unique; // EQ0000000001566233
    public $list_date; // 2007-02-06

    public function __construct( array $result ) {
        $this->margin_initial_ratio = (float)$result[ 'margin_initial_ratio' ];
        $this->rhs_tradability      = (string)$result[ 'rhs_tradability' ];
        $this->id                   = (string)$result[ 'id' ];
        $this->market               = (string)$result[ 'market' ];
        $this->simple_name          = (string)$result[ 'simple_name' ];
        $this->min_tick_size        = (string)$result[ 'min_tick_size' ];
        $this->maintenance_ratio    = (float)$result[ 'maintenance_ratio' ];
        $this->tradability          = (string)$result[ 'tradability' ];
        $this->state                = (string)$result[ 'state' ];
        $this->type                 = (string)$result[ 'type' ];
        $this->tradeable            = empty( $result[ 'tradeable' ] ) ? FALSE : TRUE;
        $this->fundamentals         = (string)$result[ 'fundamentals' ];
        $this->quote                = (string)$result[ 'quote' ];
        $this->symbol               = (string)$result[ 'symbol' ];
        $this->day_trade_ratio      = (float)$result[ 'day_trade_ratio' ];
        $this->name                 = (string)$result[ 'name' ];
        $this->tradable_chain_id    = (string)$result[ 'tradable_chain_id' ];
        $this->splits               = (string)$result[ 'splits' ];
        $this->url                  = (string)$result[ 'url' ];
        $this->country              = (string)$result[ 'country' ];
        $this->bloomberg_unique     = (string)$result[ 'bloomberg_unique' ];
        $this->list_date            = Carbon::parse( $result[ 'list_date' ] );

        // Denormalized to make other code cleaner.
        $this->instrumentId = $this->id;
    }
}