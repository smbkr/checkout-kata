<?php

use PHPUnit\Framework\TestCase;
use Smbkr\Catalogue;

class CatalogueTest extends TestCase
{
    /** @test */
    public function it_returns_a_products_single_price()
    {
        $catalogue = new Catalogue([
            'A' => 100
        ]);

        $this->assertEquals(100, $catalogue->getPriceFor('A', 1));
    }

    public function it_returns_multiples_of_base_price_for_multiple_products()
    {
        $catalogue = new Catalogue(['A' => 100]);

        $this->assertEquals(200, $catalogue->getPriceFor('A', 2));
    }

    /**
     * @test
     * @dataProvider discountProvider
     */
    public function it_returns_discounted_price_if_it_applies($product, $qty, $expected)
    {
        $products = ['A' => 100];
        $offers = [
            'A' => ['qty' => 2, 'price' => 70]
        ];
        $catalogue = new Catalogue($products, $offers);

        $this->assertEquals($expected, $catalogue->getPriceFor($product, $qty));
    }

    public function discountProvider()
    {
        return [
            ['A', 2, 70],
            ['A', 3, 170],
            ['A', 4, 140]
        ];
    }

    /**
     * @test
     */
    public function it_returns_true_if_item_is_in_catalogue()
    {
        $catalogue = new Catalogue(['A' => 100]);

        $this->assertTrue($catalogue->isAvailable('A'));
    }

    /**
     * @test
     */
    public function it_returns_false_if_item_is_not_in_catalogue()
    {
        $catalogue = new Catalogue(['A' => 100]);

        $this->assertFalse($catalogue->isAvailable('B'));
    }
}
