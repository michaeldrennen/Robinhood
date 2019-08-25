# Robinhood
![Packagist Version](https://img.shields.io/packagist/v/michaeldrennen/robinhood) ![GitHub last commit](https://img.shields.io/github/last-commit/michaeldrennen/robinhood) ![Packagist](https://img.shields.io/packagist/dt/michaeldrennen/robinhood) ![GitHub issues](https://img.shields.io/github/issues/michaeldrennen/robinhood) ![GitHub](https://img.shields.io/github/license/michaeldrennen/robinhood) ![GitHub stars](https://img.shields.io/github/stars/michaeldrennen/robinhood?style=social)

A PHP library to interact with the unofficial Robinhood API. Happy trading!

**Use at your own risk!**

```php
$robinhoodUsername    = 'some@email.com';
$robinhoodPassword    = '12345';
$robinhoodDeviceToken = 'someuuid'; // Open your web browser's inspector and examine the XHR POST request to /token. The value in the device_token param goes here.
$robinhood            = new Robinhood();
$robinhood->login( $robinhoodUsername, $robinhoodPassword, $robinhoodDeviceToken );
$quotes               = $robinhood->quotesForTickers( [ 'AAPL', 'MSFT' ] );
```

Most of the functionality is available as public methods in the Robinhood object.

The code is pretty straight forward, but I might add documentation here at some point in the future.