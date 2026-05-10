<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function delivery()
    {
        $cart = session()->get('cart', []);
        $delivery = session()->get('checkout.delivery', []);
        $user = Auth::user();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Košík je prázdny.');
        }

        $total = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return view('cart.delivery', compact('cart', 'delivery', 'total', 'user'));
    }

    public function storeDelivery(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s\/()-]{7,30}$/'],
            'delivery' => 'required|in:pickup,courier,packeta',
            'address' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'zip' => ['required', 'string', 'max:10', 'regex:/^\d{3}\s?\d{2}$/'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'first_name.required' => 'Meno je povinné.',
            'first_name.regex' => 'Meno môže obsahovať iba písmená, medzery, pomlčku a apostrof.',
            'last_name.required' => 'Priezvisko je povinné.',
            'last_name.regex' => 'Priezvisko môže obsahovať iba písmená, medzery, pomlčku a apostrof.',
            'email.required' => 'E-mail je povinný.',
            'email.email' => 'Zadaj platnú e-mailovú adresu.',
            'phone.required' => 'Telefón je povinný.',
            'phone.regex' => 'Zadaj platné telefónne číslo.',
            'delivery.required' => 'Vyber spôsob doručenia.',
            'address.required' => 'Adresa je povinná.',
            'city.required' => 'Mesto je povinné.',
            'city.regex' => 'Mesto môže obsahovať iba písmená, medzery, pomlčku a apostrof.',
            'zip.required' => 'PSČ je povinné.',
            'zip.regex' => 'PSČ musí byť vo formáte 811 01 alebo 81101.',
            'max' => 'Pole :attribute môže mať najviac :max znakov.',
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

        if (! $delivery) {
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

        if (! $delivery) {
            return redirect()->route('cart.delivery')->with('error', 'Najprv vyber spôsob doručenia.');
        }

        if (! $payment) {
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

        $paymentPrices = [
            'card' => 0,
            'cash' => 1.20,
            'bank_transfer' => 0,
        ];

        $paymentPrice = $paymentPrices[$payment['payment']] ?? 0;
        $total = $subtotal + $deliveryPrice + $paymentPrice;

        return view('cart.summary', compact(
            'cart',
            'delivery',
            'payment',
            'subtotal',
            'deliveryPrice',
            'paymentPrice',
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

        if (! $delivery) {
            return redirect()->route('cart.delivery')->with('error', 'Chýbajú údaje doručenia.');
        }

        if (! $payment) {
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
            $addressTypeId = $this->addressTypeId();
            $statusId = $this->orderStatusId();
            $paymentMethodId = $this->paymentMethodId($payment['payment']);
            $deliveryMethodId = $this->deliveryMethodId($delivery['delivery']);

            $address = Address::create([
                'street_name' => $delivery['address'],
                'city' => $delivery['city'],
                'zip_code' => $delivery['zip'],
                'state' => 'Slovensko',
                'user_id' => Auth::id(),
                'address_type_id' => $addressTypeId,
            ]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_mail' => $delivery['email'],
                'customer_first_name' => $delivery['first_name'],
                'customer_last_name' => $delivery['last_name'],
                'customer_phone' => $delivery['phone'],
                'status_id' => $statusId,
                'payment_method_id' => $paymentMethodId,
                'delivery_method_id' => $deliveryMethodId,
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

                // ZNIZENIE STAVU SKLADU
                \App\Models\Product::where('id', $item['product_id'])
                    ->decrement('stock_count', $item['quantity']);
            }
        });

        session()->forget('cart');
        session()->forget('checkout');

        if (Auth::check()) {
            app(CartService::class)->clearDatabaseCart(Auth::user());
        }

        return redirect()->route('cart.index')->with('success', 'Objednávka bola úspešne vytvorená.');
    }

    private function addressTypeId(): int
    {
        return AddressType::firstOrCreate(['name' => 'Shipping'])->id;
    }

    private function orderStatusId(): int
    {
        return OrderStatus::firstOrCreate(['name' => 'New'])->id;
    }

    private function paymentMethodId(string $payment): int
    {
        return PaymentMethod::firstOrCreate([
            'name' => $this->paymentMethodName($payment),
        ])->id;
    }

    private function deliveryMethodId(string $delivery): int
    {
        $deliveryMethod = DeliveryMethod::firstOrCreate(
            ['name' => $this->deliveryMethodName($delivery)],
            ['price' => $this->deliveryMethodPrice($delivery)]
        );

        return $deliveryMethod->id;
    }

    private function paymentMethodName(string $payment): string
    {
        return match ($payment) {
            'card' => 'Card',
            'cash' => 'Cash',
            'bank_transfer' => 'Bank transfer',
            default => 'Card',
        };
    }

    private function deliveryMethodName(string $delivery): string
    {
        return match ($delivery) {
            'pickup' => 'Pickup',
            'courier' => 'Courier',
            'packeta' => 'Packeta',
            default => 'Pickup',
        };
    }

    private function deliveryMethodPrice(string $delivery): float
    {
        return match ($delivery) {
            'courier' => 3.90,
            'packeta' => 2.49,
            default => 0,
        };
    }
}
