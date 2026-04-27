@extends('layouts.app')

@section('content')

@include('partials.checkout-steps', ['activeStep' => 'payment'])

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center g-4">

            <div class="col-12 col-lg-4">
                <div class="sticky-top cart-sticky">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-4">Košík</h2>

                            @forelse($cart as $item)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom gap-2">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-muted small">{{ $item['quantity'] }} ks</span>
                                    <strong>
                                        {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                    </strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Košík je prázdny.</p>
                            @endforelse

                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom mt-3">
                                <span class="text-dark">Doručenie</span>
                                <strong>{{ number_format($deliveryPrice, 2, ',', ' ') }} €</strong>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-4 fs-5 fw-bold">
                                <span>Spolu</span>
                                <strong>{{ number_format($total, 2, ',', ' ') }} €</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="{{ route('cart.payment.store') }}">
                            @csrf

                            <h2 class="h4 mb-2">Spôsob platby</h2>
                            <p class="text-muted mb-4">Vyber si spôsob platby, ktorý ti najviac vyhovuje.</p>

                            <div class="d-flex flex-column gap-3 mb-4">

                                <label class="payment-option border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="payment" id="cardPayment" value="card" checked>
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Platba kartou online</h3>
                                                    <p class="text-muted mb-2">Rýchla a bezpečná platba cez Visa, Mastercard alebo Maestro</p>
                                                </div>
                                                <span class="badge text-bg-dark fs-6 align-self-start">Odporúčané</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Okamžité spracovanie</span>
                                                <span>Bezpečné šifrovanie</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="payment" id="cashPayment" value="cash">
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Platba na dobierku</h3>
                                                    <p class="text-muted mb-2">Zaplatíš až pri prevzatí zásielky</p>
                                                </div>
                                                <span class="badge text-bg-secondary fs-6 align-self-start">+1.20€</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Platba pri doručení</span>
                                                <span>Hotovosť alebo karta podľa dopravcu</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="payment" id="bankTransfer" value="bank_transfer">
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Bankový prevod</h3>
                                                    <p class="text-muted mb-2">Po odoslaní objednávky ti zobrazíme údaje k platbe</p>
                                                </div>
                                                <span class="badge text-bg-light border text-dark fs-6 align-self-start">Bez poplatku</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Spracovanie po prijatí platby</span>
                                                <span>Môže trvať 1–2 pracovné dni</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="continue-btn">
                                    Pokračovať
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection