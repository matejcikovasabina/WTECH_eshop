@extends('layouts.auth')

@section('title', 'Wishlist')

@section('content')
<main class="background py-5">
    <section class="card-order cart-card wishlist-container">
        <h1 class="section-title">Wishlist</h1>

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

        @forelse($products as $product)
            @php
                $imagePath = $product->images?->first()?->image_path
                    ?? $product->accessory?->image_path
                    ?? 'images/no-image.webp';

                $productInfo = match ($product->type) {
                    'book' => $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor',
                    'giftcard' => 'Darčeková poukážka',
                    'accessory' => 'Knižný doplnok',
                    default => 'Produkt',
                };
            @endphp

            <article class="wishlist-item">
                <div class="wishlist-item-main">
                    <a href="{{ route('products.show', $product->id) }}" class="wishlist-book-cover">
                        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}">
                    </a>

                    <div class="wishlist-book-details">
                        <h2 class="book-title">
                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h2>
                        <p class="book-author">{{ $productInfo }}</p>
                    </div>
                </div>

                <div class="wishlist-item-columns">
                    <div class="status-column">
                        @if(($product->stock_count ?? 0) > 0)
                            <span class="badge text-bg-success">Na sklade</span>
                        @else
                            <span class="badge text-bg-danger">Vypredané</span>
                        @endif
                    </div>

                    <div class="price-column">
                        {{ number_format($product->price ?? 0, 2, ',', ' ') }} €
                    </div>

                    <div class="cart-column">
                        @if(($product->stock_count ?? 0) > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="btn-dark-custom" type="submit">Košík</button>
                            </form>
                        @else
                            <button class="btn-dark-custom" type="button" disabled>Košík</button>
                        @endif
                    </div>

                    <div class="cart-column">
                        <form action="{{ route('wishlist.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" type="submit">Odstrániť</button>
                        </form>
                    </div>
                </div>
            </article>

            <hr class="wishlist-divider">
        @empty
            <div class="text-center py-5">
                <p class="mb-3">Wishlist je zatiaľ prázdny.</p>
                <a href="{{ route('products.index') }}" class="btn btn-dark">Prejsť na knihy</a>
            </div>
        @endforelse
    </section>
</main>
@endsection
