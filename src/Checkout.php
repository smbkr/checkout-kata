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
     * Get subtotal including discounts for a product at the given quantity.
     * @param string $product
     * @param int $quantity
     * @return int
     */
    protected function getSubtotalForProduct($product_id, $quantity)
    {
        $subtotal = 0;
        while ($quantity > 0)
        {
            // If there's a special offer available...
            if (array_key_exists($product_id, $this->special_offers))
            {
                // And the quantity remaining qualifies for an offer
                if (($quantity % $this->special_offers[$product_id][0]) === 0) {
                    // Add the special price to the subtotal
                    $subtotal += $this->special_offers[$product_id][1];
                    // And remove the qualifying items from the basket
                    $quantity -= $this->special_offers[$product_id][0];

                    continue;
                }
            }

            // Add the item's regular price to the subtotal, and subtract one
            $subtotal += $this->products[$product_id];
            $quantity -= 1;
        }

        return $subtotal;
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

    /**
     * Guard against invalid product IDs.
     * @review could be refactored to throw an exception (InvalidProductException)
     * instead of being a boolean check.
     * @param string $value
     * @return bool
     */
    protected function guardValues($value)
    {
        if (in_array($value, array_keys($this->products)))
        {
            return true;
        }

        return false;
    }
}
