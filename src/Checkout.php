<?php

namespace Smbkr;

class Checkout
{
    /**
     * Map of product IDs => prices
     * @var array
     */
    protected $products;

    /**
     * Map of special offers.
     * Structure is string ID => [int num required, int special price]
     * @var array
     */
    protected $special_offers;

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
    public function __construct($products = [], $special_offers = [])
    {
        $this->products = $products;
        $this->special_offers = $special_offers;
    }

    /**
     * Get the total for a string of product IDs.
     * @param string $product_ids
     * @return int
     */
    public function getTotal($product_ids)
    {
        $total = 0;

        $this->populateBasket($product_ids);

        foreach ($this->basket as $product => $quantity)
        {
            while ($quantity > 0)
            {
                // If there's a special offer available...
                if (array_key_exists($product, $this->special_offers))
                {
                    // And the quantity in the basket meets the special offer's
                    // quantity required...
                    if (($quantity % $this->special_offers[$product][0]) === 0) {
                        // Add the special price to the total
                        $total += $this->special_offers[$product][1];
                        // And remove the qualifying items from the basket
                        $quantity -= $this->special_offers[$product][0];

                        continue;
                    }
                }
                // Add the item's regular price to the total, and remove that item
                // from the basket
                $total += $this->products[$product];
                $quantity -= 1;
            }
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
            if (!$this->guardValues($product)) {
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
