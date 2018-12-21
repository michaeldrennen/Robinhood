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
}