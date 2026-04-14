@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Domov</a>
            <span>&gt;</span>
            <a href="{{ route('giftcards.index') }}">Darčekové poukážky</a>
            <span>&gt;</span>
            <span class="active">{{ $giftcard->name ?? 'Detail' }}</span>
        </div>

        <div class="product-detail">
            <div class="product-image">
                <div class="main-product-img d-flex align-items-center justify-content-center" style="background: #f8f9fa; min-height: 400px;">
                    <i class="bi bi-gift" style="font-size: 10rem; color: #333;"></i>
                </div>
            </div>

            <div class="product-info">
                @if($giftcard->is_bestseller ?? false)
                    <span class="product-badge">Bestseller</span>
                @endif

                <h1>{{ $giftcard->name ?? 'Bez názvu' }}</h1>
                <h2>Darčeková poukážka</h2>

                <p class="description">
                    {{ $giftcard->description ?? 'Popis produktu nie je dostupný.' }}
                </p>

                <div class="info-pack">
                    <div class="price-stock">
                        <p class="price">{{ number_format($giftcard->price ?? 0, 2, ',', ' ') }} €</p>

                        <p class="stock">
                            @if(($giftcard->stock_count ?? 0) > 0)
                                Na sklade 
                                @if($giftcard->stock_count > 5)
                                    &gt; 5
                                @else
                                    {{ $giftcard->stock_count }}
                                @endif
                            @else
                                Vypredané
                            @endif
                        </p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="actions d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $giftcard->id }}">
                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            max="{{ $giftcard->stock_count ?? 1 }}"
                            value="1"
                            class="form-control"
                            style="width: 90px;"
                        >
                        <button class="wishlist" type="button">♡</button>
                        <button class="cart-btn" type="submit">
                            Vložiť do košíka
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <section class="container page-container-card mt-5">
        <div class="more-details">
            {{-- Sticky sidebar --}}
            <aside class="sidebar">
                <div class="book-cover d-flex align-items-center justify-content-center" style="background: #eee; height: 150px;">
                    <i class="bi bi-gift" style="font-size: 3rem;"></i>
                </div>

                <p class="book-price">
                    {{ number_format($giftcard->price ?? 0, 2, ',', ' ') }} €
                </p>

                <form action="{{ route('cart.add') }}" method="POST" class="d-flex flex-column gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $giftcard->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button class="cart-btn-sidebar" type="submit">Košík</button>
                </form>

                <nav class="side-nav">
                    <a href="#popis">Popis produktu</a>
                    <a href="#podrobnosti">Podrobnosti</a>
                    <a href="#recenzie">Recenzie</a>
                </nav>
            </aside>

            <div class="content">
                <section id="popis" class="content-section">
                    <h2>Popis</h2>
                    <p>{{ $giftcard->description ?? 'Popis nie je k dispozícii.' }}</p>
                </section>

                <section id="podrobnosti" class="content-section">
                    <h2>Podrobnosti</h2>
                    <div class="details-box">
                        <div class="detail-item">Typ: Elektronická poukážka</div>
                        <div class="detail-item">Dostupnosť: Ihneď</div>
                        <div class="detail-item">ID produktu: #{{ $giftcard->id }}</div>
                    </div>
                </section>

                {{-- Sekcia recenzií --}}
                <section id="recenzie" class="content-section">
                    <h2>Recenzie</h2>
                    <div class="reviews-summary">
                        <div class="rating-main">
                            <div class="rating-value">0.0 / 5</div>
                            <div class="rating-count">Zatiaľ žiadne hodnotenia</div>
                            <button class="review-btn" type="button">Pridať recenziu</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
@endsection