<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function delivery()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return view('cart.delivery', compact('cart', 'total'));
    }

    public function storeDelivery(Request $request)
    {
        $data = $request->validate([
            'delivery' => 'required|in:pickup,courier,packeta',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'note' => 'nullable',
        ]);

        session()->put('checkout.delivery', $data);

        return redirect()->route('cart.payment');
    }

    public function payment()
    {
        $cart = session()->get('cart', []);
        $delivery = session()->get('checkout.delivery');

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Košík je prázdny.');
        }

        if (!$delivery) {
            return redirect()->route('cart.delivery')->with('error', 'Najprv vyber spôsob doručenia.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        $deliveryPrices = [
            'pickup' => 0,
            'courier' => 3.90,
            'packeta' => 2.49,
        ];

        $deliveryPrice = $deliveryPrices[$delivery['delivery']] ?? 0;

        $total = $subtotal + $deliveryPrice;

        return view('cart.payment', compact(
            'cart',
            'delivery',
            'subtotal',
            'deliveryPrice',
            'total'
        ));
    }

    public function storePayment(Request $request)
    {
        $data = $request->validate([
            'payment' => 'required|in:card,cash,bank_transfer',
        ]);

        session()->put('checkout.payment', $data);

        return redirect()->route('cart.summary');
    }


    public function summary()
    {
        $cart = session()->get('cart', []);
        $delivery = session()->get('checkout.delivery');
        $payment = session()->get('checkout.payment');

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Košík je prázdny.');
        }

        if (!$delivery) {
            return redirect()->route('cart.delivery')->with('error', 'Najprv vyber spôsob doručenia.');
        }

        if (!$payment) {
            return redirect()->route('cart.payment')->with('error', 'Najprv vyber spôsob platby.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        $deliveryPrices = [
            'pickup' => 0,
            'courier' => 3.90,
            'packeta' => 2.49,
        ];

        $deliveryNames = [
            'pickup' => 'Osobný odber na predajni',
            'courier' => 'Kuriér na adresu',
            'packeta' => 'Packeta / výdajné miesto',
        ];

        $paymentNames = [
            'card' => 'Platba kartou online',
            'cash' => 'Platba na dobierku',
            'bank_transfer' => 'Bankový prevod',
        ];

        $deliveryPrice = $deliveryPrices[$delivery['delivery']] ?? 0;
        $total = $subtotal + $deliveryPrice;

        return view('cart.summary', compact(
            'cart',
            'delivery',
            'payment',
            'subtotal',
            'deliveryPrice',
            'total',
            'deliveryNames',
            'paymentNames'
        ));
    }
}