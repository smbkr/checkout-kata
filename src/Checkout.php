<?php

namespace Smbkr;

class Checkout
{
    protected $products;

    public function __construct($products = [], $special_offers = [])
    {
        $this->products = $products;
        $this->special_offers = $special_offers;
    }

    public function getTotal($product_ids)
    {
        $ids = str_split($product_ids);

        $total = 0;
        $products_by_id = [];
        foreach ($ids as $product_id)
        {
            if (!$this->guardValues($product_id)) {
                continue;
            }

            if (empty($products_by_id[$product_id]))
            {
                $products_by_id[$product_id] = 1;
            }
            else
            {
                $products_by_id[$product_id] ++;
            }

            if (array_key_exists($product_id, $this->special_offers))
            {
                if (($products_by_id[$product_id] % $this->special_offers[$product_id][0]) === 0) {
                    $value = $this->special_offers[$product_id][1];
                    $value = $this->special_offers[$product_id][1] - $this->products[$product_id];
                    $total += $value;
                    continue;
                }
            }

            $value = $this->products[$product_id];
            $total += $value;
        }

        return $total;
    }

    protected function guardValues($value)
    {
        if (in_array($value, array_keys($this->products)))
        {
            return true;
        }

        return false;
    }
}
