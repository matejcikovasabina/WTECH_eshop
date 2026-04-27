<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function delivery()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.delivery', compact('cart', 'total'));
    }

    public function storeDelivery(Request $request)
    {
        $data = $request->validate([
            'delivery' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'note' => 'nullable',
        ]);

        session()->put('cart.delivery', $data);

        return redirect()->route('cart.payment');
    }
}