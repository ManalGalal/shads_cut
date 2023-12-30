<?php


namespace App\Traits;


trait ServiceStockTraits {
    public function updateStock($stock, $used_amount) {
        // first 
        $original_used_amount = $used_amount;
        $used_amount -= $stock->left_over;
        if ($used_amount <= 0) {
            $stock->left_over -= $original_used_amount;
            $stock->usage += $original_used_amount;
            $stock->save();
            return;
        }
        $stock->left_over = 0;

        $quantity_used = intval($used_amount / $stock->use_times);
        $left_over = $used_amount - ($quantity_used * $stock->use_times);
        $left_over = $stock->use_times - $left_over;
        $stock->left_over = $left_over;
        $stock->quantity -= $quantity_used + 1;
        $stock->usage += $original_used_amount;
        $stock->save();
    }
}
