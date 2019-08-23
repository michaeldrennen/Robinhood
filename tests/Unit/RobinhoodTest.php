<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Dotenv\Dotenv;
use GuzzleHttp\Exception\ClientException;
use MichaelDrennen\Robinhood\Responses\Accounts\Accounts;
use MichaelDrennen\Robinhood\Responses\Accounts\InstantEligibility;
use MichaelDrennen\Robinhood\Responses\Accounts\MarginBalances;
use MichaelDrennen\Robinhood\Responses\Positions\Position;
use MichaelDrennen\Robinhood\Robinhood;
use PHPUnit\Framework\TestCase;

class RobinhoodTest extends TestCase {


    protected function getSamplePositionDataForConstructor(): array {
        return [
            'shares_held_for_stock_grants'       => 0,
            'account'                            => 'https://api.robinhood.com/accounts/ABC12345/',
            'pending_average_buy_price'          => 117.3,
            'shares_held_for_options_events'     => 0,
            'intraday_average_buy_price'         => 1,
            'url'                                => 'https://api.robinhood.com/positions/ABC12345/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/',
            'shares_held_for_options_collateral' => 0,
            'created_at'                         => '2018-12-25 00:00:00',
            'updated_at'                         => '2018-12-25 00:00:00',
            'shares_held_for_buys'               => 0,
            'average_buy_price'                  => 117.3,
            'instrument'                         => 'https://api.robinhood.com/instruments/510a1f2e-8cde-468f-957c-99ae4f528ea6/',
            'intraday_quantity'                  => 0,
            'shares_held_for_sells'              => 0,
            'shares_pending_from_options_events' => 0,
            'quantity'                           => 1,
        ];
    }


    protected function getSampleAccountDataForConstructor(): array {
        return [
            'rhs_account_number'            => '?',
            'deactivated'                   => FALSE,
            'updated_at'                    => '2018-12-25 00:00:00',
            'margin_balances'               => [
                'updated_at'                             => '2018-12-25 00:00:00',
                'gold_equity_requirement'                => 1,
                'outstanding_interest'                   => 1,
                'cash_held_for_options_collateral'       => 1,
                'uncleared_nummus_deposits'              => 1,
                'overnight_ratio'                        => 1,
                'day_trade_buying_power'                 => 1,
                'cash_available_for_withdrawal'          => 1,
                'sma'                                    => 1,
                'cash_held_for_nummus_restrictions'      => 1,
                'marked_pattern_day_trader_date'         => '2018-12-25 00:00:00',
                'unallocated_margin_cash'                => 1,
                'start_of_day_dtbp'                      => 1,
                'overnight_buying_power_held_for_orders' => 1,
                'day_trade_ratio'                        => 1,
                'cash_held_for_orders'                   => 1,
                'unsettled_debit'                        => 1,
                'created_at'                             => '2018-12-25 00:00:00',
                'cash_held_for_dividends'                => 1,
                'cash'                                   => 1,
                'start_of_day_overnight_buying_power'    => 1,
                'margin_limit'                           => 1,
                'overnight_buying_power'                 => 1,
                'uncleared_deposits'                     => 1,
                'unsettled_funds'                        => 1,
                'day_trade_buying_power_held_for_orders' => 1,
            ],
            'portfolio'                     => 'ABC12345',
            'cash_balances'                 => '?',
            'can_downgrade_to_cash'         => '?',
            'withdrawal_halted'             => FALSE,
            'cash_available_for_withdrawal' => 100,
            'type'                          => '?',
            'sma'                           => 0,
            'sweep_enabled'                 => FALSE,
            'deposit_halted'                => FALSE,
            'buying_power'                  => 200,
            'user'                          => '?',
            'max_ach_early_access_amount'   => 100,
            'option_level'                  => 3,
            'instant_eligibility'           => [
                'updated_at'         => '2018-12-25 00:00:00',
                'reason'             => '?',
                'reinstatement_date' => '2018-12-25 00:00:00',
                'reversal'           => '?',
                'state'              => '?',
            ],
            'cash_held_for_orders'          => 10,
            'only_position_closing_trades'  => '?',
            'url'                           => 'someurl',
            'positions'                     => '?',
            'created_at'                    => '1970-12-25 00:00:00',
            'cash'                          => 10,
            'sma_held_for_orders'           => 1,
            'unsettled_debit'               => 1,
            'account_number'                => '?',
            'is_pinnacle_account'           => FALSE,
            'uncleared_deposits'            => 1,
            'unsettled_funds'               => 1,
        ];
    }


    /**
     * @test
     * @group  buy_and_cancel
     * @group  logged_in_failures
     * @group  not_trading
     * @group  quotes
     * @group  markets
     */
    public function validLoginShouldGrantAccessToken(): Robinhood {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $accessToken  = $robinhood->getAccessToken();
        $refreshToken = $robinhood->getRefreshToken();
        $this->assertNotEmpty( $accessToken );
        $this->assertNotEmpty( $refreshToken );
        return $robinhood;
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   buy_and_cancel
     * @group   logged_in_failures
     */
    public function setMainAccountShouldSetAnId( Robinhood $robinhood ) {
        $robinhood->setMainAccountId();
        $mainAccountId  = $robinhood->mainAccountId;
        $mainAccountUrl = $robinhood->mainAccountUrl;
        $this->assertNotEmpty( $mainAccountId );
        $this->assertNotEmpty( $mainAccountUrl );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   markets
     */
    public function getMarketsShouldNotBeEmpty( Robinhood $robinhood ) {
        $markets = $robinhood->markets();
        $this->assertNotEmpty( $markets->markets );
    }

    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   markets
     */
    public function getMarketShouldNotBeEmpty( Robinhood $robinhood ) {
        $market = $robinhood->market( 'XASE' );
        $this->assertNotEmpty( $market->name );
    }

    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   markets
     */
    public function getMarketHoursShouldNotBeEmpty( Robinhood $robinhood ) {
        $marketHours = $robinhood->marketHours( 'XASE', Carbon::parse( '2019-01-04' ) );
        $this->assertNotEmpty( $marketHours->next_open_hours );
    }

    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   quotes
     */
    public function getQuotesForTickersShouldNotBeEmpty( Robinhood $robinhood ) {
        $quotes = $robinhood->quotesForTickers( [ 'AAPL', 'MSFT' ] );
        $this->assertNotEmpty( $quotes->quotes );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getPositionsShouldReturnAnArray( Robinhood $robinhood ) {
        $positions = $robinhood->positions()
                               ->addSymbols( $robinhood )
                               ->addLastTradePrices( $robinhood )
                               ->addMarketValueFromLastTradePrices( $robinhood )
                               ->hasShares();

        $this->assertNotEmpty( $positions );

        /**
         * @var Position $position
         */
        foreach ( $positions->objects as $position ):
            $this->assertNotEmpty( $position->symbol );
        endforeach;
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getInstrumentsBySymbolShouldNotBeEmpty( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instruments $instruments
         */
        $instruments = $robinhood->instrumentsBySymbol( 'LODE' );
        $this->assertNotEmpty( $instruments->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getInstrumentsByQueryStringShouldNotBeEmpty( Robinhood $robinhood ) {
        /**
         * @var \MichaelDrennen\Robinhood\Responses\Instruments\Instruments $instruments
         */
        $instruments = $robinhood->instrumentsByQueryString( 'finance' );
        $this->assertNotEmpty( $instruments->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getUrlShouldNotBeEmpty( Robinhood $robinhood ) {
        $results = $robinhood->url( 'https://api.robinhood.com/fundamentals/MSFT/' );
        $this->assertNotEmpty( $results );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function getRecentOrdersShouldNotBeEmpty( Robinhood $robinhood ) {
        $recentOrdres = $robinhood->getRecentOrders();
        $this->assertNotEmpty( $recentOrdres->objects );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   buy_and_cancel
     */
    public function marketBuyShouldPlaceOrder( Robinhood $robinhood ) {
        $order = $robinhood->marketBuy( $robinhood->mainAccountUrl, 'LODE', 1, FALSE );
        $this->assertNotEmpty( $order->id );

        // Quick test for us to see that pendingOrders() works and is covered.
        $orders = $robinhood->getRecentOrders()->pendingOrders();
        $this->assertNotEmpty( $orders->objects );

        return $order->id;
    }

    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   sell_and_cancel
     */
    public function marketSellShouldPlaceOrder( Robinhood $robinhood ) {
        $order = $robinhood->marketSell( $robinhood->mainAccountUrl, 'LODE', 1, FALSE );
        $this->assertNotEmpty( $order->id );

        $robinhoodResponse = $robinhood->cancelOrder( $order->id );
        $this->assertEmpty( $robinhoodResponse );
    }

    /**
     * @test
     * @depends marketBuyShouldPlaceOrder
     */
    public function getOrderInformationShouldNotBeEmpty( string $orderId ) {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $order = $robinhood->getOrderInformation( $orderId );
        $this->assertNotEmpty( $order->id );
        return $orderId;
    }


    /**
     * @test
     * @depends marketBuyShouldPlaceOrder
     * @group   buy_and_cancel
     */
    public function cancelOrderShouldNotBeEmpty( string $orderId ) {
        $dotenv = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $robinhoodResponse = $robinhood->cancelOrder( $orderId );
        $this->assertEmpty( $robinhoodResponse );
    }


    /**
     * @test
     * @group bad_cancel
     */
    public function cancelOrderWithInvalidOrderIdShouldThrowException() {
        $this->expectException( \Exception::class );
        $invalidOrderId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        $dotenv         = new Dotenv( __DIR__ );
        $dotenv->load();
        $robinhood = new Robinhood();
        $robinhood->login( getenv( 'USERNAME' ), getenv( 'PASSWORD' ) );
        $robinhood->cancelOrder( $invalidOrderId );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function attemptToBuyInvalidTickerShouldThrowException( Robinhood $robinhood ) {
        $this->expectException( \Exception::class );
        $invalidTicker = 'INVALIDTICKER';
        $robinhood->setMainAccountId();
        $robinhood->marketBuy( $robinhood->mainAccountUrl, $invalidTicker, 1 );
    }

    /**
     * @test
     */
    public function badLoginShouldThrowException() {
        $this->expectException( ClientException::class );
        $robinhood = new Robinhood();
        $robinhood->login( 'foo', 'bar' );
    }

    /**
     * @test
     */
    public function callToUrlWhenNotLoggedInShouldThrowException() {
        $this->expectException( \Exception::class );
        $robinhood = new Robinhood();
        $results   = $robinhood->url( 'https://api.robinhood.com/fundamentals/MSFT/' );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function attemptToBuyStockOverOneDollarShouldTriggerLogicInGetBidPrice( Robinhood $robinhood ) {
        $tickerOverOneDollar = 'ODT';
        $robinhood->setMainAccountId();
        $order = $robinhood->marketBuy( $robinhood->mainAccountUrl, $tickerOverOneDollar, 1 );
        $this->assertNotEmpty( $order->id );
        $robinhoodResponse = $robinhood->cancelOrder( $order->id );
        $this->assertEmpty( $robinhoodResponse );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     */
    public function attemptToSellStockOverOneDollarShouldTriggerLogicInGetBidPrice( Robinhood $robinhood ) {
        $tickerOverOneDollar = 'ODT';
        $robinhood->setMainAccountId();
        $order = $robinhood->marketSell( $robinhood->mainAccountUrl, $tickerOverOneDollar, 1, FALSE );
        $this->assertNotEmpty( $order->id );
        $robinhoodResponse = $robinhood->cancelOrder( $order->id );
        $this->assertEmpty( $robinhoodResponse );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function getInstrumentOnETPShouldReturnQuoteOnET( Robinhood $robinhood ) {
        $instruments = $robinhood->instrumentsBySymbol( 'ETP' )->addSymbols( $robinhood );
        $this->assertEquals( 'ET', $instruments->objects[ 0 ]->symbol );
    }


    /**
     * @test
     * @group not_trading
     */
    public function positionWithInvalidInstrumentShouldThrowExceptionWhenGettingInstrumentId() {
        $this->expectException( \Exception::class );
        $arrayForConstructor                 = $this->getSamplePositionDataForConstructor();
        $arrayForConstructor[ 'instrument' ] = 'NotValidInstrumentUrl';
        $position                            = new Position( $arrayForConstructor );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function addSymbolWithoutInstrumentIdShouldThrowException( Robinhood $robinhood ) {
        $this->expectException( \Exception::class );
        $position               = new Position( $this->getSamplePositionDataForConstructor() );
        $position->instrumentId = NULL;
        $position->addSymbol( $robinhood );
    }

    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function addLastTradePriceBeforeAddSymbolShouldThrowException( Robinhood $robinhood ) {
        $this->expectException( \Exception::class );
        $position = new Position( $this->getSamplePositionDataForConstructor() );
        $position->addLastTradePrice( $robinhood );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function addLastTradePriceWithInvalidTickerShouldHaveNullLastTradePrice( Robinhood $robinhood ) {
        $position = new Position( $this->getSamplePositionDataForConstructor() );
        $position->addSymbol( $robinhood );
        $position->symbol = 'INVALIDTICKER';
        $position->addLastTradePrice( $robinhood );
        $this->assertNull( $position->lastTradePrice );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function addMarketValueBeforeAddingLastTradePriceThrowException( Robinhood $robinhood ) {
        $this->expectException( \Exception::class );
        $position = new Position( $this->getSamplePositionDataForConstructor() );
        $position->addSymbol( $robinhood );
        $position->addMarketValueFromLastTradePrice( $robinhood );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function hasSharesShouldReturnTrueIfPositionHasShares() {
        $position = new Position( $this->getSamplePositionDataForConstructor() );
        $this->assertTrue( $position->hasShares() );
    }


    /**
     * @test
     * @depends validLoginShouldGrantAccessToken
     * @group   not_trading
     */
    public function hasSharesShouldReturnFalseIfPositionHasNoShares() {
        $position           = new Position( $this->getSamplePositionDataForConstructor() );
        $position->quantity = 0;
        $this->assertFalse( $position->hasShares() );
    }


    /**
     * @test
     * @group   not_trading
     */
    public function getMainAccountIdForUserWithNoAccountsShouldThrowException() {
        $this->expectException( \Exception::class );
        $arrayForConstructor = [ 'results' => [] ];
        //$arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        //$arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $accounts = new Accounts( $arrayForConstructor );
        $accounts->getMainAccountId();

    }


    /**
     * @test
     * @group   not_trading
     */
    public function getMainAccountUrlForUserWithNoAccountsShouldThrowException() {
        $this->expectException( \Exception::class );
        $arrayForConstructor = [ 'results' => [] ];
        //$arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        //$arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $accounts = new Accounts( $arrayForConstructor );
        $accounts->getMainAccountUrl();

    }


    /**
     * @test
     * @group   not_trading
     */
    public function getMainAccountIdForUserWithMultipleAccountsShouldThrowException() {
        $this->expectException( \Exception::class );
        $arrayForConstructor                = [ 'results' => [] ];
        $arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $accounts                           = new Accounts( $arrayForConstructor );
        $accounts->getMainAccountId();

    }


    /**
     * @test
     * @group   not_trading
     */
    public function getMainAccountUrlForUserWithMultipleAccountsShouldThrowException() {
        $this->expectException( \Exception::class );
        $arrayForConstructor                = [ 'results' => [] ];
        $arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $arrayForConstructor[ 'results' ][] = $this->getSampleAccountDataForConstructor();
        $accounts                           = new Accounts( $arrayForConstructor );
        $accounts->getMainAccountUrl();
    }


    /**
     * @test
     * @group not_trading
     */
    public function addExceptionsToResponseShouldAddExceptions() {
        $position = new Position( $this->getSamplePositionDataForConstructor() );
        $this->assertFalse( $position->hasExceptions() );
        $position->addException( new \Exception( "This is a test exception." ) );
        $exceptions = $position->getExceptions();
        $this->assertNotEmpty( $exceptions );
        $this->assertTrue( $position->hasExceptions() );
    }

    /**
     * @test
     * @group account_id
     */
    public function getAccountIdShouldReturnAnAccountId() {
        $position  = new Position( $this->getSamplePositionDataForConstructor() );
        $accountId = $position->getAccountId();
        $this->assertEquals( 'ABC12345', $accountId );
    }


}