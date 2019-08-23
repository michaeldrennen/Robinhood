<?php

namespace MichaelDrennen\Robinhood\Responses;

class RobinhoodResponse {

    protected $exceptions = [];


    public function addException( \Exception $exception ) {
        $this->exceptions[] = $exception;
    }

    public function getExceptions(): array {
        return $this->exceptions;
    }

    public function hasExceptions(): bool {
        if ( count( $this->exceptions ) > 0 ):
            return TRUE;
        endif;
        return FALSE;
    }

    /**
     * The instrument id is available in the instrument field, but there are circumstances where I want the instrument
     * id by itself. This function uses a regular expression to parse it out.
     * Call this from the child's constructor.
     * @param string $instrument Ex: https://api.robinhood.com/instruments/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/
     * @return string Ex: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @throws \Exception
     */
    protected function getInstrumentIdFromInstrument( string $instrument ): string {
        $regexPattern = '/.*\/(.*)\/$/';
        preg_match( $regexPattern, $instrument, $matches );
        if ( ! isset( $matches[ 1 ] ) ):
            throw new \Exception( "Unable to find the instrument id from this string: " . $instrument );
        endif;
        return $matches[ 1 ];
    }


}