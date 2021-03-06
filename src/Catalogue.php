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

    /**
     * @param array $products
     * @param array $special_offers Ideally this would be an array of Offer
     * objects implementing an interface with getQtyRequired() and getDiscountedPrice()
     * methods or similar.
     */
    public function __construct($products, $special_offers = [])
    {
        $this->products = $products;
        $this->special_offers = $special_offers;
    }

    /**
     * Determines if a product is on offer.
     * @param string $product_code
     * @return bool
     */
    protected function onOffer($product_code)
    {
        return in_array($product_code, array_keys($this->special_offers));
    }

    /**
     * Get total price for n number of a product.
     * @param string $product_code
     * @param int $quantity
     * @return int
     */
    public function getPriceFor($product_code, $quantity = 1)
    {
        $total = 0;

        // If the product is on offer
        if ($this->onOffer($product_code)) {
            // And we meet the min quantity required
            $offer_qty_required = $this->special_offers[$product_code]['qty'];
            while ($quantity >= $offer_qty_required) {
                // Add the special price to the total, and subtract the
                // qualifying items
                $total += $this->special_offers[$product_code]['price'];
                $quantity -= $offer_qty_required;
            }
        }

        $total += $this->products[$product_code] * $quantity;

        return $total;
    }

    /**
     * Determine if a product is in the catalogue.
     * @param string $product_code
     * @return bool
     */
    public function isAvailable($product_code)
    {
        return in_array($product_code, array_keys($this->products));
    }
}
