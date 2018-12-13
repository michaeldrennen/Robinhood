<?php

namespace MichaelDrennen\Robinhood\Responses\Accounts;

use Carbon\Carbon;

class InstantEligibility {
    public $updated_at; //
    public $reason; //
    public $reinstatement_date; //
    public $reversal; //
    public $state; // ok

    public function __construct( array $instantEligibility ) {
        $this->updated_at         = Carbon::parse( $instantEligibility[ 'updated_at' ] );
        $this->reason             = (string)$instantEligibility[ 'reason' ];
        $this->reinstatement_date = Carbon::parse( $instantEligibility[ 'reinstatement_date' ] );
        $this->reversal           = (string)$instantEligibility[ 'reversal' ];
        $this->state              = (string)$instantEligibility[ 'state' ];
    }
}