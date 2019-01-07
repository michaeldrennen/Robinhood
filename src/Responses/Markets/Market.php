<?php

namespace MichaelDrennen\Robinhood\Responses\Markets;

class Market {
    public $website;        // URL      URL for that market's website
    public $city;           // String	City the market is based in
    public $name;           // String	Full name of the market
    public $url;            // URL	    Endpoint for this market
    public $country;        // String	Country this market is located in
    public $todays_hours;   // URL	    Endpoint containing today's operating hours for that market
    public $operating_mic;  // String	Operator's Market Identifier Code
    public $acronym;        // String	Acronym for that market
    public $timezone;       // String	Timezone the market operates in
    public $mic;            // String	Market Identifier Code

    public function __construct( array $market ) {
        $this->website       = (string)$market[ 'website' ];
        $this->city          = (string)$market[ 'city' ];
        $this->name          = (string)$market[ 'name' ];
        $this->url           = (string)$market[ 'url' ];
        $this->country       = (string)$market[ 'country' ];
        $this->todays_hours  = (string)$market[ 'todays_hours' ];
        $this->operating_mic = (string)$market[ 'operating_mic' ];
        $this->acronym       = (string)$market[ 'acronym' ];
        $this->timezone      = (string)$market[ 'timezone' ];
        $this->mic           = (string)$market[ 'mic' ];
    }
}