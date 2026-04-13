@extends('layouts.app')

@section('content')
@include('partials.checkout-steps', ['activeStep' => 'cart'])

<main class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h2 mb-4">Košík</h1>

                        @forelse($cart as $item)
                            <div class="row align-items-center gy-3 py-3 border-bottom">
                                <div class="col-12 col-md-2 col-lg-1">
                                    <img
                                        src="{{ asset('images/' . ($item['cover_image'] ?? 'adults.webp')) }}"
                                        alt="{{ $item['name'] }}"
                                        class="img-fluid rounded shadow-sm cart-img"
                                    >
                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <h3 class="h5 mb-1">
                                        <a href="{{ route('books.show', $item['product_id']) }}" class="text-decoration-none text-dark">
                                            {{ $item['name'] }}
                                        </a>
                                    </h3>
                                    <p class="text-muted mb-0">{{ $item['author'] ?? 'Neznámy autor' }}</p>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    @if(($item['stock_count'] ?? 0) > 0)
                                        <span class="badge text-bg-success">Na sklade</span>
                                    @else
                                        <span class="badge text-bg-danger">Vypredané</span>
                                    @endif
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-outline-secondary">-</button>
                                        </form>

                                        <button type="button" class="btn btn-outline-secondary disabled">
                                            {{ $item['quantity'] }}
                                        </button>

                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-outline-secondary">+</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-6 col-md-1 col-lg-1">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button class="btn btn-outline-danger btn-sm ms-4" aria-label="Odstrániť" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2 text-end">
                                    <strong class="fs-5">
                                        {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                    </strong>
                                </div>
                            </div>
                        @empty
                            <div class="py-4">
                                <p class="mb-0">Váš košík je zatiaľ prázdny.</p>
                            </div>
                        @endforelse

                        <div class="row align-items-end pt-4 gy-3">
                            <div class="col-12 col-md-6">
                                <div class="alert alert-success mb-0" role="alert">
                                    Doprava zadarmo pri objednávke nad 80 €
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="d-flex flex-column align-items-md-end gap-3">
                                    <div class="d-flex align-items-center gap-3 fs-4 fw-bold">
                                        <span>Spolu</span>
                                        <strong>{{ number_format($total, 2, ',', ' ') }} €</strong>
                                    </div>

                                    <button class="continue-btn" {{ empty($cart) ? 'disabled' : '' }}>
                                        Pokračovať
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</main>
@endsection