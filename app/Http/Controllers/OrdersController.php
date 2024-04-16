<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct(
        public OrderService $services
    ) {
    }
    public function showOrderPage()
    {
        return Inertia::render('Dashboard', ['content' => 'order']);
    }

    public function getOrders()
    {
        return $this->services->allOrders();
    }


    public function createOrder(Request $request)
    {
        $order = new Order();
        $order->payment_reference_number = $request->checkoutId;
        $order->total =  $request->total;
        $order->user_id = auth()->id();
        $order->save();
    }
}
