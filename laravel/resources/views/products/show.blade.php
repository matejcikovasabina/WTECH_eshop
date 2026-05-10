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
                <a href="{{ route('products.index') }}">Knihy</a>
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
                <div class="main-image-container" style="position: relative;">
                    {{-- zobrazenie sipiek pre viac obrazkov --}}
                    @if($product->images->count() > 1)
                        <button class="slider-arrow left" onclick="changeImage(-1)">&#10094;</button>
                    @endif

                    <img 
                        id="current-main-img"
                        src="{{ $mainImage ? asset($mainImage) : asset('images/no-image.webp') }}" 
                        class="main-product-img" 
                        alt="{{ $product->name ?? 'Produkt' }}"
                    >

                    @if($product->images->count() > 1)
                        <button class="slider-arrow right" onclick="changeImage(1)">&#10095;</button>
                    @endif
                </div>

                {{-- nahlad vsetkych obrazkov --}}
                @if($product->images->count() > 1)
                    <div class="thumbnail-bar d-flex gap-2 mt-2">
                        @foreach($product->images as $index => $img)
                            <img 
                                src="{{ asset($img->image_path) }}" 
                                class="img-thumbnail"
                                onclick="setImage({{ $index }}, '{{ asset($img->image_path) }}')"
                            >
                        @endforeach
                    </div>
                @endif
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
                @endif

                <p class="description">
                    {{ $description ?? 'Popis produktu nie je dostupný.' }}
                </p>

                @if($type === 'book')
                    <p class="meta">
                        {{ $product->book?->binding?->name ?? 'Neznáma väzba' }}
                        • {{ $product->book?->language?->name ?? 'Neznámy jazyk' }}
                        • {{ $product->book?->publisher?->name ?? 'Neznáme vydavateľstvo' }}
                        @if($product->book?->year)
                            , {{ $product->book->year }}
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

                    <div class="actions d-flex align-items-center gap-2">
                        @auth
                            <form
                                action="{{ $isWishlisted ? route('wishlist.destroy', $product) : route('wishlist.store') }}"
                                method="POST"
                                class="m-0"
                            >
                                @csrf
                                @if($isWishlisted)
                                    @method('DELETE')
                                @else
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                @endif

                                <button
                                    class="wishlist {{ $isWishlisted ? 'active' : '' }}"
                                    type="submit"
                                    aria-label="{{ $isWishlisted ? 'Odstrániť z wishlistu' : 'Pridať do wishlistu' }}"
                                >
                                    {{ $isWishlisted ? '♥' : '♡' }}
                                </button>
                            </form>
                        @else
                            <a class="wishlist d-inline-flex align-items-center justify-content-center text-decoration-none" href="{{ route('login') }}" aria-label="Prihlásiť sa pre wishlist">
                                ♡
                            </a>
                        @endauth

                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-2 m-0">
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

                            <button class="cart-btn" type="submit">
                                Vložiť do košíka
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($product->book && $showAuthorSlider)
        @include('partials.book-slider', [
            'title' => 'Ďalšie od ' . ($product->book->authors->pluck('full_name')->implode(', ') ?: 'autora'),
            'items' => $moreFromAuthor
        ])
    @endif

    @if($product->book && $recommended->isNotEmpty())
        @include('partials.book-slider', [
            'title' => 'Odporúčame',
            'items' => $recommended
        ])
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
                </nav>
            </aside>

            <div class="content">
                <section id="popis" class="content-section">
                    <h2>Popis</h2>

                    @if(!empty($description))
                        <p>{{ $description }}</p>
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

    // pole vsetkych obrazkov pripravene z php
    const productImages = @json($product->images->pluck('image_path')->map(fn($path) => asset($path)));
    let currentIndex = 0;

    function changeImage(direction) {
        if (productImages.length === 0) return;
        
        currentIndex += direction;
        
        if (currentIndex >= productImages.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = productImages.length - 1;
        
        updateImageDisplay();
    }

    function setImage(index, path) {
        currentIndex = index;
        updateImageDisplay();
    }

    function updateImageDisplay() {
        const mainImg = document.getElementById('current-main-img');
        mainImg.src = productImages[currentIndex];
        
        // zvyrazni aktivny nahlad
        document.querySelectorAll('.img-thumbnail').forEach((thumb, idx) => {
            thumb.style.opacity = (idx === currentIndex) ? '1' : '0.6';
        });
    }
</script>
@endsection
