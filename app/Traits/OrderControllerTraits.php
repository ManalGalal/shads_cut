<?php

namespace App\Traits;

use App\Models\Milestone;
use Throwable;

trait OrderControllerTraits {

    /**
     * This function can be used for canceling orders as well
     */
    public function descreaseUserPointsOnRefundingCompletedOrder($user, $order) {
        try {
            $refunded_order_points = Milestone::where("order_id", $order->id)
                ->sum("points") ?? 0;
                
            $user->points -= $refunded_order_points;
            $user->save();
            Milestone::where("order_id", $order->id)
                ->delete();
        } catch (Throwable $th) {
            // TODO: Log this in the future
        }
    }
}
