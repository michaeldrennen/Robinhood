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
            if ( empty( $order->executions->executions ) ):
                $unexecutedOrders[] = $order;
            endif;
        endforeach;
        $this->objects = $unexecutedOrders;
        return $this;
    }

    /**
     * @return $this
     */
    public function cancelledOrders() {
        $filledOrders = [];
        /**
         * @var $order \MichaelDrennen\Robinhood\Responses\Orders\Order
         */
        foreach ( $this->objects as $order ):
            if ( 'cancelled' == $order->state ):
                $filledOrders[] = $order;
            endif;
        endforeach;
        $this->objects = $filledOrders;
        return $this;
    }

    /**
     * @return $this
     */
    public function filledOrders() {
        $filledOrders = [];
        /**
         * @var $order \MichaelDrennen\Robinhood\Responses\Orders\Order
         */
        foreach ( $this->objects as $order ):
            if ( 'filled' == $order->state ):
                $filledOrders[] = $order;
            endif;
        endforeach;
        $this->objects = $filledOrders;
        return $this;
    }

    /**
     * @TODO Polish this function. There is a lot that can go into this. WAPP needed for PNL on sales.
     * @return array
     * @throws \Exception
     */
    public function orderStatsSummary() {
        $stats = [
            'buy'  => [
                'numFilled'    => 0,
                'numCancelled' => 0,
                'shares'       => 0,
                'proceeds'     => 0,
                'marketValue'  => 0,
                'pnl'          => 0,
            ],
            'sell' => [
                'numFilled'    => 0,
                'numCancelled' => 0,
                'shares'       => 0,
                'proceeds'     => 0,
                'marketValue'  => 0,
                'pnl'          => 0,
            ],
        ];

        /**
         * @var \MichaelDrennen\Robinhood\Responses\Orders\Order $order
         */
        foreach ( $this->objects as $order ):
            if ( $order->wasFilled() ):
                switch ( $order->side ):
                    case 'buy':
                        $stats[ 'buys' ][ 'numFilled' ]   += 1;
                        $stats[ 'buys' ][ 'shares' ]      += $order->quantity;
                        $stats[ 'buys' ][ 'proceeds' ]    += $order->proceeds();
                        $stats[ 'buys' ][ 'marketValue' ] += $order->marketValueFromLastTradePrice();
                        $stats[ 'buys' ][ 'pnl' ]         += $order->pnlFromLastTradePrice();
                        break;

                    case 'sell':
                        $stats[ 'sells' ][ 'numFilled' ] += 1;
                        $stats[ 'sells' ][ 'shares' ]    += $order->quantity;
                        $stats[ 'sells' ][ 'proceeds' ]  += $order->proceeds();
                        //$stats[ 'sells' ][ 'marketValue' ] += $order->marketValueFromLastTradePrice();
                        //$stats[ 'sells' ][ 'pnl' ]         += $order->pnlFromLastTradePrice();
                        break;

                    default:
                        /**
                         * @TODO Test and add code for options trades here.
                         */
                        throw new \Exception( "Add code for this side of an order: " . $order->side );
                        break;

                endswitch;

            endif;
        endforeach;


        return $stats;
    }


}