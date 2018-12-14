<?php

namespace MichaelDrennen\Robinhood\Responses\Instruments;

class Instrument {

    public $margin_initial_ratio; //0.8000
    public $rhs_tradability; //tradable
    public $id; //510a1f2e-8cde-468f-957c-99ae4f528ea6
    public $market; //https://api.robinhood.com/markets/XNAS/
    public $simple_name; //MadrigalPharmaceuticals
    public $min_tick_size; //
    public $maintenance_ratio; //0.7500
    public $tradability; //tradable
    public $state; //active
    public $type; //stock
    public $tradeable; //1
    public $fundamentals; //https://api.robinhood.com/fundamentals/MDGL/
    public $quote; //https://api.robinhood.com/quotes/MDGL/
    public $symbol; //MDGL
    public $day_trade_ratio; //0.2500
    public $name; //MadrigalPharmaceuticals
    public $tradable_chain_id; //8d76e826-c232-4ed2-bf89-7bd844135ebd
    public $splits; //https://api.robinhood.com/instruments/510a1f2e-8cde-468f-957c-99ae4f528ea6/splits/
    public $url; //https://api.robinhood.com/instruments/510a1f2e-8cde-468f-957c-99ae4f528ea6/
    public $country; //US
    public $bloomberg_unique; //EQ0000000001566233
    public $list_date; //2007-02-06


    public function __construct( array $result ) {
        $this->margin_initial_ratio = $result[ 'margin_initial_ratio' ];
        $this->rhs_tradability      = $result[ 'rhs_tradability' ];
        $this->id                   = $result[ 'id' ];
        $this->market               = $result[ 'market' ];
        $this->simple_name          = $result[ 'simple_name' ];
        $this->min_tick_size        = $result[ 'min_tick_size' ];
        $this->maintenance_ratio    = $result[ 'maintenance_ratio' ];
        $this->tradability          = $result[ 'tradability' ];
        $this->state                = $result[ 'state' ];
        $this->type                 = $result[ 'type' ];
        $this->tradeable            = $result[ 'tradeable' ];
        $this->fundamentals         = $result[ 'fundamentals' ];
        $this->quote                = $result[ 'quote' ];
        $this->symbol               = $result[ 'symbol' ];
        $this->day_trade_ratio      = $result[ 'day_trade_ratio' ];
        $this->name                 = $result[ 'name' ];
        $this->tradable_chain_id    = $result[ 'tradable_chain_id' ];
        $this->splits               = $result[ 'splits' ];
        $this->url                  = $result[ 'url' ];
        $this->country              = $result[ 'country' ];
        $this->bloomberg_unique     = $result[ 'bloomberg_unique' ];
        $this->list_date            = $result[ 'list_date' ];
    }


}