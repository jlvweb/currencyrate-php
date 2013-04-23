## CurrencyRate PHP
A simple PHP class for converting exchange rate in realtime using Google Currency Converter or European Central Bank.

###How to use with Google Currency Converter (default)
```php
<?php

$CurrencyRate = new CurrencyRate();
$eur_to_usd = $CurrencyRate->convert("EUR","USD"); 
echo 'Exchange rate Euro to US Dollars: ' . $eur_to_usd;

?>
```

###How to use with European Central Bank
```php
<?php

$CurrencyRate = new CurrencyRate("ecb"); // Specifying ecb in the construction of the class
$eur_to_usd = $CurrencyRate->convert("EUR","USD");
echo 'Exchange rate Euro to US Dollars: ' . $eur_to_usd;

?>
```

###How to convert different amount
```php
<?php

$CurrencyRate = new CurrencyRate(); 
$eur_to_usd = $CurrencyRate->convert("EUR","USD", 9.99); // Specifying the amount as the third argument
echo '9.99 Euro in US Dollars: ' . $eur_to_usd;

?>
```