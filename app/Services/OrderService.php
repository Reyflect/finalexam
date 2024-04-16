<?php


namespace App\Services;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderService
{

    public function allOrders()
    {
        return Order::all();
    }
}
