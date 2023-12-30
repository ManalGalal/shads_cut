<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class CustomerOrder
{
    use HttpErrors;
    public function handle(Request $request, Closure $next)
    {   
        $order = $request->route("order");
        if (!$order || gettype($order) === "string"){
            return $this->NOT_FOUND(__("errors.order_not_found"));
        }
        if ($order->user_id != $request->user()->id){
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
