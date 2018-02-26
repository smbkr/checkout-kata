<?php

use PHPUnit\Framework\TestCase;
use Smbkr\Checkout;
use Smbkr\Catalogue;

class CheckoutTest extends TestCase
{
    /**
     * Tests that Checkout can fail gracefully if given an empty order.
     * @test
     */
    public function it_returns_0_for_empty_string()
    {
        $catalogueMock = $this->createMock(Catalogue::class);
        $checkout = new Checkout($catalogueMock);

        $this->assertEquals(0, $checkout->getTotal(""));
    }

    /**
     * Tests that Checkout is calling hte getPriceFor method on Catalogue to
     * determine a product's cost.
     * @test
     */
    public function it_returns_the_value_for_a_product_code()
    {
        $catalogueMock = $this->createMock(Catalogue::class);
        $catalogueMock->method('isAvailable')
            ->willReturn(true);
        $catalogueMock->method('getPriceFor')
            ->willReturn(300);
        $checkout = new Checkout($catalogueMock);

        $this->assertEquals(300, $checkout->getTotal("A"));
    }

    /**
     * Test that Checkout will loop over each product type, calling getPriceFor
     * on Catalogue for each product.
     * @test
     */
    public function it_sums_the_price_for_multiple_products()
    {
        $catalogueMock = $this->createMock(Catalogue::class);
        $catalogueMock->method('isAvailable')
            ->willReturn(true);
        $catalogueMock->method('getPriceFor')
            ->will($this->onConsecutiveCalls(100, 200, 300, 400));
        $checkout = new Checkout($catalogueMock);

        $this->assertEquals(1000, $checkout->getTotal("ABCD"));
    }

    /**
     * @test
     * @review what is the desired behaviour when given an invalid ID? throw an
     * exception, or recover and continue by ignoring the invalid ID? My feeling
     * is that if we accept "" (empty string) returns zero, to go with the latter.
     */
    public function it_guards_against_invalid_products()
    {
        $catalogueMock = $this->createMock(Catalogue::class);
        $catalogueMock->method('isAvailable')
            ->willReturn(false);
        $checkout = new Checkout($catalogueMock);

        $this->assertEquals(0, $checkout->getTotal('Z'));
    }
}
