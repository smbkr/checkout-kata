<?php

use PHPUnit\Framework\TestCase;
use Smbkr\Checkout;
use Smbkr\Catalogue;

class CatalogueCheckoutTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_sum_of_base_prices_when_there_are_no_discounts()
    {
        // Given a catalogue with no discounts
        $catalogue = new Catalogue(
            ['A' => 100, 'B' => 200, 'C' => 150],
            []
        );

        // When we add products to the checkout
        $checkout = new Checkout($catalogue);
        $result = $checkout->getTotal('AABC');

        // Then we should get the sum of base prices
        $this->assertEquals(550, $result);
    }

    /**
     * @test
     */
    public function it_returns_subtotal_with_discounts_when_offers_apply()
    {
        // Given an item in the catalogue is on offer
        $catalogue = new Catalogue(
            ['A' => 100, 'B' => 50, 'C' => 70],
            ['C' => ['qty' => 2, 'price' => 50]]
        );

        // When we add some of that item to the checkout
        $checkout = new Checkout($catalogue);
        $result = $checkout->getTotal('ABCABC');

        // Then we should receive the discounted price
        $this->assertEquals(350, $result);
    }

    /**
     * @test
     */
    public function it_doesnt_apply_discounts_when_qty_is_not_met()
    {
        // Given an item in the catalogue is on offer
        $catalogue = new Catalogue(
            ['A' => 100, 'B' => 50, 'C' => 70],
            ['C' => ['qty' => 10, 'price' => 20]]
        );

        // When we add too few of that item to the checkout to qualify
        $checkout = new Checkout($catalogue);
        $result = $checkout->getTotal('ABCABC');

        // Then we should be charged full price
        $this->assertEquals(440, $result);
    }
}
