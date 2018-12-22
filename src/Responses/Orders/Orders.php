<?php

namespace MichaelDrennen\Robinhood\Responses\Orders;

use MichaelDrennen\Robinhood\Responses\RobinhoodResponseForInstruments;

class Orders extends RobinhoodResponseForInstruments {


    /**
     * Orders constructor.
     * @param array $response A parsed response from the Robinhood API
     * @throws \Exception
     */
    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->objects[] = new Order( $result );
        endforeach;
    }


    /**
     * Orders don't necessarily get executed right away. Perhaps you:
     *  - placed the order after market hours, or
     *  - placed a buy with a limit price that is lower than the current market price.
     * @return $this
     */
    public function pendingOrders() {
        $unexecutedOrders = [];
        /**
         * @var $order \MichaelDrennen\Robinhood\Responses\Orders\Order
         */
        foreach ( $this->objects as $order ):
            if ( empty( $order->executions ) ):
                $unexecutedOrders[] = $order;
            endif;
        endforeach;
        $this->objects = $unexecutedOrders;
        return $this;
    }
}