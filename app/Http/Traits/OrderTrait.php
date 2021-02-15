<?php

namespace App\Http\Traits;

use App\Models\OrderDetail;

trait OrderTrait {

    /**
     * Calculate the price of an order
     *
     * @param int $id
     * 
     * @return float
     */
    public function calculatePrice($orderId) {
        $products = OrderDetail::where('order_id',$orderId)->with(['product'])->get();
        $value = 0;
        foreach($products as $item) {
            $value += $item->product->price;
        }

        return $value;
    }

}