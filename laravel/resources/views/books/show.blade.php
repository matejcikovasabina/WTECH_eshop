@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Domov</a>
            <span>&gt;</span>
            <a href="{{ route('books.overview') }}">Knihy</a>
            <span>&gt;</span>
            <a href="#">Beletria</a>
            <span>&gt;</span>
            <span>{{ $book->product?->name ?? 'Detail knihy' }}</span>
        </div>

        <div class="product-detail">
            <div class="product-image">
                <img
                    src="{{ asset('images/' . ($book->cover_image ?? 'adults.webp')) }}"
                    class="main-product-img"
                    alt="{{ $book->product?->name ?? 'Kniha' }}"
                >
            </div>

            <div class="product-info">
                @if($book->is_bestseller ?? false)
                    <span class="product-badge">Bestseller</span>
                @endif

                <h1>{{ $book->product?->name ?? 'Bez názvu' }}</h1>
                <h2>{{ $book->authors->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}</h2>

                @if(!empty($book->subtitle))
                    <p class="subtitle">{{ $book->subtitle }}</p>
                @elseif(!empty($book->description))
                    <p class="subtitle">{{ \Illuminate\Support\Str::limit($book->description, 140, '...') }}</p>
                @endif

                <p class="description">
                    {{ $book->description ?? 'Popis knihy nie je dostupný.' }}
                </p>

                <p class="meta">
                    {{ $book->binding?->name ?? 'Neznáma väzba' }}
                    • {{ $book->language?->name ?? 'Neznámy jazyk' }}
                    • {{ $book->publisher?->name ?? 'Neznáme vydavateľstvo' }}
                    @if($book->year)
                        , {{ $book->year }}
                    @endif
                </p>

                <div class="info-pack">
                    <div class="price-stock">
                        <p class="price">{{ number_format($book->product?->price ?? 0, 2, ',', ' ') }} €</p>

                        <p class="stock">
                            @if(($book->product?->stock_count ?? 0) > 0)
                                Na sklade
                                @if($book->product->stock_count > 5)
                                    &gt; 5
                                @else
                                    {{ $book->product->stock_count }}
                                @endif
                            @else
                                Vypredané
                            @endif
                        </p>
                    </div>

                    <div class="actions">
                        <button class="wishlist" type="button">♡</button>
                        <button class="cart-btn" type="button" onclick="addToCart({{ $book->product_id }})">
                            Vložiť do košíka
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($showAuthorSlider)
        <section class="container page-container-card">
            <h2 class="mb-3">
                Ďalšie od
                {{ $book->authors->pluck('full_name')->implode(', ') ?: 'autora' }}
            </h2>

            <div class="slider-wrapper" style="position: relative;">
                <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <div class="book-slider">
                    @foreach($moreFromAuthor as $item)
                        <a href="{{ route('books.show', $item->product_id) }}" class="text-decoration-none text-dark">
                            <div class="cardd">
                                <div class="card-img-placeholder">
                                    <img
                                        src="{{ asset('images/' . ($item->cover_image ?? 'adults.webp')) }}"
                                        class="cardd-img"
                                        alt="{{ $item->product?->name ?? 'Kniha' }}"
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

    @if($recommended->count())
        <section class="container page-container-card">
            <h2 class="mb-3">Tiež by sa Vám mohlo páčiť</h2>

            <div class="slider-wrapper" style="position: relative;">
                <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <div class="book-slider">
                    @foreach($recommended as $item)
                        <a href="{{ route('books.show', $item->product_id) }}" class="text-decoration-none text-dark">
                            <div class="cardd">
                                <div class="card-img-placeholder">
                                    <img
                                        src="{{ asset('images/' . ($item->cover_image ?? 'adults.webp')) }}"
                                        class="cardd-img"
                                        alt="{{ $item->product?->name ?? 'Kniha' }}"
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
                        src="{{ asset('images/' . ($book->cover_image ?? 'adults.webp')) }}"
                        class="sidebar-book-img"
                        alt="{{ $book->product?->name ?? 'Kniha' }}"
                    >
                </div>

                <p class="book-price">
                    {{ number_format($book->product?->price ?? 0, 2, ',', ' ') }} €
                </p>

                <button class="cart-btn-sidebar" type="button" onclick="addToCart({{ $book->product_id }})">
                    Košík
                </button>

                <nav class="side-nav">
                    <a href="#popis">Popis knihy</a>
                    <a href="#podrobnosti">Podrobnosti</a>
                    <a href="#recenzie">Recenzie</a>
                </nav>
            </aside>

            <div class="content">
                <section id="popis" class="content-section">
                    <h2>Popis</h2>

                    @if(!empty($book->description))
                        <p class="short-desc">
                            {{ \Illuminate\Support\Str::limit($book->description, 80, '...') }}
                        </p>

                        <p>{{ $book->description }}</p>
                    @else
                        <p>Popis knihy nie je dostupný.</p>
                    @endif
                </section>

                <section id="podrobnosti" class="content-section">
                    <h2>Podrobnosti</h2>

                    <div class="details-box">
                        <div class="detail-item">Väzba: {{ $book->binding?->name ?? '-' }}</div>
                        <div class="detail-item">Rozmer: {{ $book->width ?? '-' }} × {{ $book->height ?? '-' }} × {{ $book->depth ?? '-' }} mm</div>
                        <div class="detail-item">Hmotnosť: {{ $book->weight ?? '-' }} g</div>
                        <div class="detail-item">ISBN: {{ $book->isbn ?? '-' }}</div>
                        <div class="detail-item">Rok vydania: {{ $book->year ?? '-' }}</div>
                        <div class="detail-item">Jazyk: {{ $book->language?->name ?? '-' }}</div>
                        <div class="detail-item">Vydavateľstvo: {{ $book->publisher?->name ?? '-' }}</div>
                        <div class="detail-item">Počet strán: {{ $book->pages_num ?? '-' }}</div>
                    </div>
                </section>

                <section id="recenzie" class="content-section">
                    <h2>Recenzie</h2>

                    <div class="reviews-summary">
                        <div class="rating-main">
                            <div class="rating-value">{{ number_format($book->rating ?? 0, 1) }} / 5</div>
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
                                            $rating = $book->rating ?? 0;
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