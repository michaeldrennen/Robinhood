<?php

namespace MichaelDrennen\Robinhood\Responses\Accounts;

class Accounts {
    public $accounts = [];


    public function __construct( array $response ) {
        foreach ( $response[ 'results' ] as $i => $result ):
            $this->accounts[] = new Account( $result );
        endforeach;
    }


    /**
     * @return string Ex: ABC12345
     * @throws \Exception
     */
    public function getMainAccountId(): string {
        if ( sizeof( $this->accounts ) > 1 ):
            throw new \Exception( "This Robinhood user has more than one account. They will need to get all of their accounts, and then specify which id they want to set as their main account." );
        endif;

        if ( sizeof( $this->accounts ) < 1 ):
            throw new \Exception( "Curiously enough, this Robinhood user does not have an account. Maybe it's not approved?" );
        endif;

        /**
         * @var $mainAccount \MichaelDrennen\Robinhood\Responses\Accounts\Account
         */
        $mainAccount = $this->accounts[ 0 ];

        return (string)$mainAccount->account_number;
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function getMainAccountUrl(): string {
        if ( sizeof( $this->accounts ) > 1 ):
            throw new \Exception( "This Robinhood user has more than one account. They will need to get all of their accounts, and then specify which id they want to set as their main account." );
        endif;

        if ( sizeof( $this->accounts ) < 1 ):
            throw new \Exception( "Curiously enough, this Robinhood user does not have an account. Maybe it's not approved?" );
        endif;

        /**
         * @var $mainAccount \MichaelDrennen\Robinhood\Responses\Accounts\Account
         */
        $mainAccount = $this->accounts[ 0 ];

        return (string)$mainAccount->url;
    }

}