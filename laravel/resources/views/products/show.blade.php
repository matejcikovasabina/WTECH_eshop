@extends('layouts.app')

@section('content')

@php
    $type = $product->book ? 'book' : ($product->giftcard ? 'giftcard' : ($product->accessory ? 'accessory' : 'product'));

    $mainImage = $product->images->first()?->image_path;

    if (!$mainImage && $product->accessory?->image_path) {
        $mainImage = $product->accessory->image_path;
    }

    $description = $product->accessory?->description ?? $product->description;
@endphp
<main>
    <section class="container page-container-card">
        {{-- DYNAMICKÝ BREADCRUMB PODĽA TYPU --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Domov</a>
            <span>&gt;</span>
            @if($type === 'book')
                <a href="{{ route('books.index') }}">Knihy</a>
            @elseif($type === 'giftcard')
                <a href="{{ route('giftcards.index') }}">Darčekové poukážky</a>
            @else
                <a href="{{ route('accessories.index') }}">Knižné doplnky</a>
            @endif
            <span>&gt;</span>
            @if($type === 'book')
                <a href="#">Beletria</a>
                <span>&gt;</span>
            @endif
            <span>{{ $product->name ?? 'Detail produktu' }}</span>
        </div>

        <div class="product-detail">
            <div class="product-image">
                <img
                    src="{{ $mainImage ? asset($mainImage) : asset('images/no-image.webp') }}"
                    class="main-product-img"
                    alt="{{ $product->name ?? 'Produkt' }}"
                >
            </div>

            <div class="product-info">
                @if($product->is_bestseller ?? false)
                    <span class="product-badge">Bestseller</span>
                @endif

                <h1>{{ $product->name ?? 'Bez názvu' }}</h1>

                <h2>
                    @if($type === 'book')
                        {{ $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                    @elseif($type === 'giftcard')
                        Darčeková poukážka
                    @else
                        Knižný doplnok
                    @endif
                </h2>

                @if(!empty($product->subtitle ?? null))
                    <p class="subtitle">{{ $product->subtitle }}</p>
                @elseif(!empty($product->description))
                    <p class="subtitle">{{ \Illuminate\Support\Str::limit($product->description, 140, '...') }}</p>
                @endif

                <p class="description">
                    {{ $product->description ?? 'Popis produktu nie je dostupný.' }}
                </p>

                @if($type === 'book')
                    <p class="meta">
                        {{ $product->binding?->name ?? 'Neznáma väzba' }}
                        • {{ $product->language?->name ?? 'Neznámy jazyk' }}
                        • {{ $product->publisher?->name ?? 'Neznáme vydavateľstvo' }}
                        @if($product->year)
                            , {{ $product->year }}
                        @endif
                    </p>
                @endif

                <div class="info-pack">
                    <div class="price-stock">
                        <p class="price">{{ number_format($product->price ?? 0, 2, ',', ' ') }} €</p>

                        <p class="stock">
                            @if(($product->stock_count ?? 0) > 0)
                                Na sklade
                                @if($product->stock_count > 5)
                                    &gt; 5
                                @else
                                    {{ $product->stock_count }}
                                @endif
                            @else
                                Vypredané
                            @endif
                        </p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="actions d-flex align-items-center gap-2">
                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            max="{{ $product->stock_count ?? 1 }}"
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

    @if($type === 'book' && isset($showAuthorSlider) && $showAuthorSlider)
        <section class="container page-container-card">
            <h2 class="mb-3">
                Ďalšie od
                {{ $product->authors->pluck('full_name')->implode(', ') ?: 'autora' }}
            </h2>

            <div class="slider-wrapper" style="position: relative;">
                <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <div class="book-slider">
                    @foreach($moreFromAuthor as $item)
                        <a href="{{ route('books.show', $item->id) }}" class="text-decoration-none text-dark">
                            <div class="cardd">
                                <div class="card-img-placeholder">
                                    <img
                                        src="{{ asset('images/' . ($item->cover_image ?? 'default.webp')) }}"
                                        class="cardd-img"
                                        alt="{{ $item->name ?? 'Kniha' }}"
                                    >
                                </div>

                                <div class="cardd-body">
                                    <h6 class="cardd-body-title">
                                        {{ $item->product?->name ?? 'Bez názvu' }}
                                    </h6>

                                    <p class="cardd-body-author">
                                        {{ $item->authors->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                                    </p>

                                    <p class="cardd-body-price">
                                        {{ number_format($item->product?->price ?? 0, 2, ',', ' ') }} €
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <button class="carousel-control-next" type="button" onclick="scrollSlider(this, 1)">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>
    @endif


    <section class="container page-container-card mt-5">
        <div class="more-details">
            <aside class="sidebar">
                <div class="book-cover">
                    <img
                        src="{{ $mainImage ? asset($mainImage) : asset('images/no-image.webp') }}"
                        class="main-product-img"
                        alt="{{ $product->name ?? 'Produkt' }}"
                    >
                </div>

                <p class="book-price">
                    {{ number_format($product->price ?? 0, 2, ',', ' ') }} €
                </p>

                <form action="{{ route('cart.add') }}" method="POST" class="d-flex flex-column gap-2">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">
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

                    @if(!empty($product->description))
                        <p class="short-desc">
                            {{ \Illuminate\Support\Str::limit($product->description, 80, '...') }}
                        </p>

                        <p>{{ $product->description }}</p>
                    @else
                        <p>Popis produktu nie je dostupný.</p>
                    @endif
                </section>

                <section id="podrobnosti" class="content-section">
                    <h2>Podrobnosti</h2>

                    <div class="details-box">
                        @if($type === 'book')
                        <div class="detail-item">Väzba: {{ $product->book?->binding?->name ?? '-' }}</div>
                        <div class="detail-item">Rozmer: {{ $product->book?->width ?? '-' }} x {{ $product->book?->height ?? '-' }} x {{ $product->book?->depth ?? '-' }} mm</div>
                        <div class="detail-item">Hmotnosť: {{ $product->book?->weight ?? '-' }} g</div>
                        <div class="detail-item">ISBN: {{ $product->book?->isbn ?? '-' }}</div>
                        <div class="detail-item">Rok vydania: {{ $product->book?->year ?? '-' }}</div>
                        <div class="detail-item">Jazyk: {{ $product->book?->language?->name ?? '-' }}</div>
                        <div class="detail-item">Vydavateľstvo: {{ $product->book?->publisher?->name ?? '-' }}</div>
                        <div class="detail-item">Počet strán: {{ $product->book?->pages_num ?? '-' }}</div>
                        @else
                            <div class="detail-item">
                                Typ produktu: 
                                @if($type === 'giftcard')
                                    Darčeková poukážka
                                @else
                                    Knižný doplnok
                                @endif
                            </div>
                            <div class="detail-item">Dostupnosť: Na sklade</div>
                        @endif
                    </div>
                </section>

                <section id="recenzie" class="content-section">
                    <h2>Recenzie</h2>

                    <div class="reviews-summary">
                        <div class="rating-main">
                            <div class="rating-value">{{ number_format($product->rating ?? 0, 1) }} / 5</div>
                            <div class="rating-count">Počet hodnotení zatiaľ nie je dostupný</div>
                            <button class="review-btn" type="button">Pridať recenziu</button>
                        </div>

                        <div class="rating-breakdown">
                            <div class="rating-row">
                                <span>★★★★★</span>
                            </div>
                            <div class="rating-row">
                                <span>★★★★☆</span>
                            </div>
                            <div class="rating-row">
                                <span>★★★☆☆</span>
                            </div>
                            <div class="rating-row">
                                <span>★★☆☆☆</span>
                            </div>
                            <div class="rating-row">
                                <span>★☆☆☆☆</span>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-list">
                        <article class="review-item">
                            <div class="review-content">
                                <div class="review-header">
                                    <span class="review-author">ReadIt</span>
                                    <span class="review-stars">
                                        @php
                                            $rating = $product->rating ?? 0;
                                            $fullStars = floor($rating);
                                        @endphp

                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $fullStars ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                </div>

                                <p>
                                    Recenzie zatiaľ nie sú dostupné.
                                </p>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>

<script>
    function scrollSlider(button, direction) {
        const wrapper = button.closest(".slider-wrapper");
        const slider = wrapper.querySelector(".book-slider");
        const card = slider.querySelector(".cardd");

        if (!card) return;

        const gap = 20;
        const scrollAmount = card.offsetWidth + gap;

        const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
        const currentScroll = slider.scrollLeft;

        if (direction === 1) {
            if (currentScroll + scrollAmount >= maxScrollLeft) {
                slider.scrollTo({
                    left: 0,
                    behavior: "smooth"
                });
            } else {
                slider.scrollBy({
                    left: scrollAmount,
                    behavior: "smooth"
                });
            }
        }

        if (direction === -1) {
            if (currentScroll <= 0) {
                slider.scrollTo({
                    left: maxScrollLeft,
                    behavior: "smooth"
                });
            } else {
                slider.scrollBy({
                    left: -scrollAmount,
                    behavior: "smooth"
                });
            }
        }
    }
</script>
@endsection