# Checkout Kata

Adapted from [http://codekata.com/kata/kata09-back-to-the-checkout/](http://codekata.com/kata/kata09-back-to-the-checkout/)

The `Checkout` class has a method `getTotal` which takes a string of product IDs, ie. "A', "AABC",
etc, and returns the sum of the price of these products. Products can also have special discount
pricing that is applied when a minimum quantity is ordered.

## Running

Assuming you have PHP >= 7.1 and [https://getcomposer.org/](Composer) installed, run `composer install` to install the dependencies. Run tests with `vendor/bin/phpunit`.
