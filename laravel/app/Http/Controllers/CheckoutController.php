<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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

    public function placeOrder()
    {
        $cart = session()->get('cart', []);
        $delivery = session()->get('checkout.delivery');
        $payment = session()->get('checkout.payment');

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Košík je prázdny.');
        }

        if (!$delivery) {
            return redirect()->route('cart.delivery')->with('error', 'Chýbajú údaje doručenia.');
        }

        if (!$payment) {
            return redirect()->route('cart.payment')->with('error', 'Chýba spôsob platby.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        $deliveryPrices = [
            'pickup' => 0,
            'courier' => 3.90,
            'packeta' => 2.49,
        ];

        $paymentPrices = [
            'card' => 0,
            'cash' => 1.20,
            'bank_transfer' => 0,
        ];

        $deliveryPrice = $deliveryPrices[$delivery['delivery']] ?? 0;
        $paymentPrice = $paymentPrices[$payment['payment']] ?? 0;

        $total = $subtotal + $deliveryPrice + $paymentPrice;

        DB::transaction(function () use ($cart, $delivery, $payment, $total) {
            $address = Address::create([
                'street_name' => $delivery['address'],
                'city' => $delivery['city'],
                'zip_code' => $delivery['zip'],
                'state' => 'Slovensko',
                'user_id' => Auth::id(),
                'address_type_id' => 1,
            ]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_mail' => null,
                'status_id' => 1,
                'payment_method_id' => $this->mapPaymentMethod($payment['payment']),
                'delivery_method_id' => $this->mapDeliveryMethod($delivery['delivery']),
                'billing_address_id' => $address->id,
                'shipping_address_id' => $address->id,
                'total_price' => $total,
                'created_at' => Carbon::now(),
                'discount_id' => null,
                'note' => $delivery['note'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }
        });

        session()->forget('cart');
        session()->forget('checkout');

        return redirect()->route('cart.index')->with('success', 'Objednávka bola úspešne vytvorená.');
    }

    private function mapPaymentMethod(string $payment): int
    {
        return match ($payment) {
            'card' => 1,
            'cash' => 2,
            'bank_transfer' => 3,
            default => 1,
        };
    }

    private function mapDeliveryMethod(string $delivery): int
    {
        return match ($delivery) {
            'pickup' => 1,
            'courier' => 2,
            'packeta' => 3,
            default => 1,
        };
    }
   
}