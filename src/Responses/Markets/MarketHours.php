<?php

namespace MichaelDrennen\Robinhood\Responses\Markets;

use Carbon\Carbon;

class MarketHours {
    public $closes_at;              // 	ISO 8601	Time the market closes at
    public $extended_opens_at;      // 	ISO 8601	Time the market opens at including extended hours
    public $next_open_hours;        //	URL	        Endpoint containing the next day's opening hours
    public $previous_open_hours;    //	URL	        Endpoint containing the previous day's opening hours
    public $is_open;                //	Bool	    Is the market open that day?
    public $extended_closes_at;     //	ISO 8601	Time the market closes at including extended hours
    public $date;                   //	ISO 8601	Current date
    public $opens_at;               //	ISO 8601	Acronym for thaat market

    public $previousOpenDate;
    public $nextOpenDate;

    public function __construct(array $marketHours) {
        $this->closes_at           = Carbon::parse($marketHours['closes_at']);
        $this->extended_opens_at   = Carbon::parse($marketHours['extended_opens_at']);
        $this->next_open_hours     = (string)$marketHours['next_open_hours'];
        $this->previous_open_hours = (string)$marketHours['previous_open_hours'];
        $this->is_open             = (bool)$marketHours['is_open'];
        $this->extended_closes_at  = Carbon::parse($marketHours['extended_closes_at']);
        $this->date                = Carbon::parse($marketHours['date']);
        $this->opens_at            = Carbon::parse($marketHours['opens_at']);

        // Denormalized fields
        $this->previousOpenDate    = $this->getDateFromLink($this->previous_open_hours);
        $this->nextOpenDate        = $this->getDateFromLink($this->next_open_hours);
    }

    /**
     * @param string $urlToHours Ex: https://api.robinhood.com/markets/XNYS/hours/2019-02-26/
     * @return Carbon
     */
    protected function getDateFromLink(string $urlToHours): Carbon {
        $urlParts   = explode('/', $urlToHours);
        $urlParts   = array_filter($urlParts);
        $stringDate = end($urlParts);
        return Carbon::parse($stringDate);
    }


}