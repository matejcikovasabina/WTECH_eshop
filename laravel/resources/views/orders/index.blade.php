@extends('layouts.auth')

@section('title', 'Moje objednávky')

@section('content')
<main class="background py-5">
    <section class="card-order cart-card">
        <h1 class="section-title">Moje objednávky</h1>

        @forelse($orders as $order)
            <div class="order-row">
                <div class="order-col order-number">
                    <strong>#{{ $order->id }}</strong>
                    <div class="invoice">
                        faktúra <span class="dot circle-placeholder"></span>
                    </div>
                </div>

                <div class="order-col order-date order-text">
                    {{ $order->created_at->format('d.m.Y') }}
                </div>

                <div class="order-col order-status order-text">
                    {{ $order->status ?? 'spracováva sa' }}
                </div>

                <div class="order-col order-price order-text">
                    {{ number_format($order->total_price ?? 0, 2, ',', ' ') }} €
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <h4>Zatiaľ nemáte žiadne objednávky.</h4>
                <p class="text-muted">Keď si niečo objednáte, zobrazí sa to tu.</p>
                <a href="{{ route('products.index') }}" class="btn btn-dark mt-3">
                    Prejsť na knihy
                </a>
            </div>
        @endforelse
    </section>
</main>
@endsection