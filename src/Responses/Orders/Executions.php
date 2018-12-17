<?php

namespace MichaelDrennen\Robinhood\Responses\Orders;

class Executions {

    /**
     * @var array An array of Execution objects.
     */
    public $executions = [];


    /**
     * Executions constructor.
     * @param array $executions An multi-dimensional array of execution data.
     * @throws \Exception
     */
    public function __construct( array $executions ) {
        foreach ( $executions as $i => $execution ):
            $this->executions[] = new Execution( $execution );
        endforeach;
    }



}