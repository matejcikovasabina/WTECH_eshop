@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Domov</a>
            <span>&gt;</span>
            {{-- Predpokladám, že máš route 'accessories.index' --}}
            <a href="{{ route('accessories.index') }}">Doplnky</a>
            <span>&gt;</span>
            <span class="active">{{ $accessory->name ?? 'Detail' }}</span>
        </div>

        <div class="product-detail">
            <div class="product-image">
                {{-- Ikona tašky pre doplnky --}}
                <div class="main-product-img d-flex align-items-center justify-content-center" style="background: #f8f9fa; min-height: 400px;">
                    <i class="bi bi-bag-check" style="font-size: 10rem; color: #333;"></i>
                </div>
            </div>

            <div class="product-info">
                @if($accessory->is_bestseller ?? false)
                    <span class="product-badge">Bestseller</span>
                @endif

                <h1>{{ $accessory->name ?? 'Bez názvu' }}</h1>
                <h2>Doplnkový tovar</h2>

                <p class="description">
                    {{ $accessory->description ?? 'Popis produktu nie je dostupný.' }}
                </p>

                <div class="info-pack">
                    <div class="price-stock">
                        <p class="price">{{ number_format($accessory->price ?? 0, 2, ',', ' ') }} €</p>

                        <p class="stock">
                            @if(($accessory->stock_count ?? 0) > 0)
                                Na sklade 
                                @if($accessory->stock_count > 5)
                                    &gt; 5
                                @else
                                    {{ $accessory->stock_count }}
                                @endif
                            @else
                                Vypredané
                            @endif
                        </p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="actions d-flex align-items-center gap-2">
                        @csrf
                        {{-- Používame product_id ako v tvojom predošlom kóde --}}
                        <input type="hidden" name="product_id" value="{{ $accessory->product_id }}">
                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            max="{{ $accessory->stock_count ?? 1 }}"
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
                    <i class="bi bi-bag-check" style="font-size: 3rem;"></i>
                </div>

                <p class="book-price">
                    {{ number_format($accessory->price ?? 0, 2, ',', ' ') }} €
                </p>

                <form action="{{ route('cart.add') }}" method="POST" class="d-flex flex-column gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $accessory->product_id }}">
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
                    <p>{{ $accessory->description ?? 'Popis nie je k dispozícii.' }}</p>
                </section>

                <section id="podrobnosti" class="content-section">
                    <h2>Podrobnosti</h2>
                    <div class="details-box">
                        <div class="detail-item">Typ: Doplnkový tovar</div>
                        <div class="detail-item">Dostupnosť: Na sklade</div>
                        <div class="detail-item">ID produktu: #{{ $accessory->product_id }}</div>
                    </div>
                </section>

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