<?php

namespace Smbkr;

class Checkout
{
    /**
     * Holds the catalogue of prices and offers.
     * @var Catalogue
     */
    protected $catalogue;

    /**
     * Holds items to be purchased.
     * string key is product ID, int value is the quantity for that item.
     * @var array
     */
    protected $basket = [];

    /**
     * @param array $products
     * @param array $special_offers
     */
    public function __construct($catalogue)
    {
        $this->catalogue = $catalogue;
    }

    /**
     * Get the total for a string of product IDs.
     * @param string $product_ids
     * @return int
     */
    public function getTotal($product_ids)
    {
        $this->populateBasket($product_ids);

        $total = 0;
        foreach ($this->basket as $product => $quantity)
        {
            $total += $this->catalogue->getPriceFor($product, $quantity);
        }

        return $total;
    }

    /**
     * Split string of IDs into a basket of products.
     * @param string $product_ids
     * @return void
     */
    protected function populateBasket($product_ids)
    {
        // Split string of product IDs, and add each item to the basket, so
        // that items are grouped by product ID.
        foreach (str_split($product_ids) as $product)
        {
            if (!$this->catalogue->isAvailable($product)) {
                continue;
            }

            // If a product is not in the basket, add a new key with the product
            // ID, and set the quantity to 1, otherwise, increment the current
            // quantity by 1.
            if (empty($this->basket[$product])) {
                $this->basket[$product] = 1;
            } else {
                $this->basket[$product] ++;
            }
        }
    }
}
