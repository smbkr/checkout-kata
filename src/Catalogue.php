<?php

namespace Smbkr;

class Catalogue
{
    /**
     * Holds map of products in the catalogue and their base price.
     * @var array
     */
    protected $products;

    /**
     * Map of products with a special offer price. Values are:
     * product code => [quantity, offer price]
     * @var array
     */
    protected $special_offers;

    public function __construct($products, $special_offers = [])
    {
        $this->products = $products;
        $this->special_offers = $special_offers;
    }

    protected function onOffer($product_code)
    {
        return in_array($product_code, array_keys($this->special_offers));
    }

    public function getPriceFor($product_code, $quantity = 1)
    {
        if ($quantity === 1 ||
            !$this->onOffer($product_code)
        ) {
            return $this->products[$product_code];
        }

        $total = 0;

        $offer_qty_required = $this->special_offers[$product_code]['qty'];
        while ($quantity >= $offer_qty_required) {
            $total += $this->special_offers[$product_code]['price'];
            $quantity -= $offer_qty_required;
        }

        while ($quantity > 0) {
            $total += $this->getPriceFor($product_code);
            $quantity --;
        }

        return $total;
    }
}
