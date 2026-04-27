@extends('layouts.app')

@section('content')

@include('partials.checkout-steps', ['activeStep' => 'summary'])

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h4 mb-4">Sumár objednávky</h2>

                        <div class="mb-4">
                            <h3 class="h5 mb-3">Objednané produkty</h3>

                            @foreach($cart as $item)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom gap-2">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-muted small">{{ $item['quantity'] }} ks</span>
                                    <strong>
                                        {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                    </strong>
                                </div>
                            @endforeach
                        </div>

                        <div class="card border rounded-4 mb-4">
                            <div class="card-body">
                                <h3 class="h5 mb-3">Doručenie</h3>

                                <div class="row gy-2">
                                    <div class="col-12 col-md-6">
                                        <span class="fw-semibold">Spôsob:</span>
                                        <span class="text-muted">
                                            {{ $deliveryNames[$delivery['delivery']] ?? 'Neznáme doručenie' }}
                                        </span>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <span class="fw-semibold">Adresa:</span>
                                        <span class="text-muted">{{ $delivery['address'] }}</span>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <span class="fw-semibold">Mesto:</span>
                                        <span class="text-muted">{{ $delivery['city'] }}</span>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <span class="fw-semibold">PSČ:</span>
                                        <span class="text-muted">{{ $delivery['zip'] }}</span>
                                    </div>

                                    @if(!empty($delivery['note']))
                                        <div class="col-12">
                                            <span class="fw-semibold">Poznámka:</span>
                                            <span class="text-muted">{{ $delivery['note'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card border rounded-4 mb-4">
                            <div class="card-body">
                                <h3 class="h5 mb-3">Platba</h3>

                                <div>
                                    <span class="fw-semibold">Spôsob platby:</span>
                                    <span class="text-muted">
                                        {{ $paymentNames[$payment['payment']] ?? 'Neznáma platba' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card border rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span>Medzisúčet</span>
                                    <span>{{ number_format($subtotal, 2, ',', ' ') }} €</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span>Doručenie</span>
                                    <span>{{ number_format($deliveryPrice, 2, ',', ' ') }} €</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 mt-2 border-top fs-5 fw-bold">
                                    <span>Spolu</span>
                                    <strong>{{ number_format($total, 2, ',', ' ') }} €</strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <form method="POST" action="{{ route('checkout.placeOrder') }}">
                                @csrf
                                <button type="submit" class="continue-btn">Objednať</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection