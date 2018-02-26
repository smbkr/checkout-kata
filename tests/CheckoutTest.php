<?php

use PHPUnit\Framework\TestCase;
use Smbkr\Checkout;

class CheckoutTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_0_for_empty_string()
    {
        $checkout = new Checkout(array(), array());

        $this->assertEquals(0, $checkout->getTotal(""));
    }

    /**
     * @test
     */
    public function it_returns_the_value_for_a_product_code()
    {
        $products = [
            'A' => 300,
            'B' => 500,
            'C' => 700,
            'D' => 1000
        ];

        $checkout = new Checkout($products);

        $this->assertEquals($products['A'], $checkout->getTotal("A"));
    }

    /**
     * @test
     */
    public function it_sums_the_price_for_multiple_products()
    {
        $products = [
            'A' => 300,
            'B' => 500,
            'C' => 700,
            'D' => 1000
        ];

        $checkout = new Checkout($products);

        $this->assertEquals(2500, $checkout->getTotal("ABCD"));
    }

    /**
     * @test
     * @review what is the desired behaviour when given an invalid ID? throw an exception, or recover and continue by ignoring the invalid ID?
     */
    public function it_guards_against_invalid_products()
    {
        $products = [
            'A' => 300,
            'B' => 500,
            'C' => 700,
            'D' => 1000
        ];

        $checkout = new Checkout($products);

        $this->assertEquals(0, $checkout->getTotal('Z'));
        $this->assertEquals(300, $checkout->getTotal('AZ'));
    }

    /**
     * @test
     */
    public function it_applies_discount_for_special_offer_prices()
    {
        $products = [
            'A' => 300,
            'B' => 500,
            'C' => 700,
            'D' => 1000
        ];
        $special_offers = [
            'C' => [2, 1000]
        ];

        $checkout = new Checkout($products, $special_offers);

        $this->assertEquals(1000, $checkout->getTotal('CC'));
        $this->assertEquals(1600, $checkout->getTotal('ACAC'));
        $this->assertEquals(2000, $checkout->getTotal('CCCC'));
        $this->assertEquals(1700, $checkout->getTotal('CCC'));
    }
}
