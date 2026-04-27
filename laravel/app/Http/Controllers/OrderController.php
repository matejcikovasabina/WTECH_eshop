<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        
        $orders = Order::where('user_id', auth()->id())
            ->with(['status', 'items.product'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.index', compact('orders'));
    }
}