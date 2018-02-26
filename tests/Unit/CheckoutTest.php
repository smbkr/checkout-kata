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
        $checkout = $this->getTestSubject();

        $this->assertEquals(0, $checkout->getTotal(""));
    }

    /**
     * @test
     */
    public function it_returns_the_value_for_a_product_code()
    {
        $checkout = $this->getTestSubject();

        $this->assertEquals(300, $checkout->getTotal("A"));
    }

    /**
     * @test
     */
    public function it_sums_the_price_for_multiple_products()
    {
        $checkout = $this->getTestSubject();

        $this->assertEquals(2500, $checkout->getTotal("ABCD"));
    }

    /**
     * @test
     * @review what is the desired behaviour when given an invalid ID? throw an
     * exception, or recover and continue by ignoring the invalid ID? My feeling
     * is that if we accept "" (empty string) returns zero, to go with the latter.
     */
    public function it_guards_against_invalid_products()
    {
        $checkout = $this->getTestSubject();

        $this->assertEquals(0, $checkout->getTotal('Z'));
        $this->assertEquals(300, $checkout->getTotal('AZ'));
    }

    /**
     * @test
     * @dataProvider offersProvider
     */
    public function it_applies_discount_for_special_offer_prices($order, $expected_total)
    {
        $checkout = $this->getTestSubject();

        $this->assertEquals($expected_total, $checkout->getTotal($order));
    }

    /**
     * Provide test data and expectations for ::it_applies_discount_for_special_offer_prices
     * @return array
     */
    public function offersProvider()
    {
        return [
            ['CC', 1000],
            ['ACAC', 1600],
            ['CCCC', 2000],
            ['CCC', 1700]
        ];
    }
}
