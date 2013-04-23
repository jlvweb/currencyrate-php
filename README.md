## CurrencyRate PHP
A simple PHP class for converting exchange rate in realtime using Google Currency Converter or European Central Bank
Author: Johan Lingvall

###How to use
```php
<?php

$CurrencyRate = new CurrencyRate();
$eur_to_usd = $CurrencyRate->convert("EUR","USD","google"); // Change "google" to "ecb" for European Central Bank
echo 'Exchange rate Euro to US Dollars is ' . $eur_to_usd;

?>
```