PrivatBank API client
==============

Unofficial PHP wrapper for [PrivatBank API](https://api.privatbank.ua).

For now can only fetch card **balance** and **statements**.

Inspired by [yehor-manzhula/privatbank-api](https://github.com/yehor-manzhula/privatbank-api).

Feel free for contribute ;)

# Installation

```sh
composer require cyanofresh/privatbank-api
```

# Usage

```php
<?php

require './vendor/autoload.php';

$merchant = new \CyanoFresh\PrivatBankAPI\Merchant('MERCHANT ID HERE', 'MERCHANT PASSWORD HERE');

// Get balance of the card
$merchant->getBalanceInfo('CARD NUMBER HERE');

// Get statements for card for last 1 week
$merchant->getStatements('CARD NUMBER HERE', (new DateTime())->modify('-1 week'), new DateTime());
```
