@extends('layouts.auth')

@section('title', 'ReadIt')

@section('content')
<main>
    <div class="container page-container-card">
        <div id="mainCarousel" class="carousel slide main-carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="main-slider-img-placeholder slide-jarnvypredaj">
                        <img class="main-carousel-img" src="{{ asset('images/book-bookshelves.webp') }}" alt="Jarný výpredaj">
                        <div class="carousel-caption-custom">
                            <h1>Jarný výpredaj</h1>
                            <p>Získaj zľavy až do <strong>30 %</strong> na vybrané tituly!</p>
                            <a href="#" class="btn btn-primary btn-lg">Nakupovať teraz</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="main-slider-img-placeholder slide-poukazky">
                        <img class="main-carousel-img" src="{{ asset('images/book-lamp.webp') }}" alt="Darčekové poukážky">
                        <div class="carousel-caption-custom">
                            <h1>Darčekové poukážky</h1>
                            <p>Nevieš si vybrať? Poteš svojich blízkych slobodou výberu.</p>
                            <a href="#" class="btn btn-outline-light btn-lg">Pozrieť poukážky</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="main-slider-img-placeholder slide-doplnky">
                        <img class="main-carousel-img" src="{{ asset('images/book-bookmark.webp') }}" alt="Doplnky ku knihám">
                        <div class="carousel-caption-custom">
                            <h1>Doplnky ku knihám</h1>
                            <p>Záložky, lampičky na čítanie a štýlové obaly, ktoré si zamiluješ.</p>
                            <a href="#" class="btn btn-primary btn-lg">Chcem doplnky</a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <section class="container page-container-card mt-5">
        <h2>Novinky</h2>
        <div class="slider-wrapper" style="position: relative;">
            <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <div class="book-slider">
                @forelse($newBooks as $book)
                    <div class="cardd">
                        <div class="card-img-placeholder">
                            <img
                                src="{{ asset('images/placeholder.webp') }}"
                                class="cardd-img"
                                alt="{{ $book->product?->name ?? 'Kniha' }}"
                            >
                        </div>

                        <div class="cardd-body">
                            <h6 class="cardd-body-title">
                                {{ $book->product?->name ?? 'Bez názvu' }}
                            </h6>

                            <p class="cardd-body-author">
                                {{ $book->authors->pluck('full_name')->join(', ') }}
                            </p>

                            <p class="cardd-body-price">
                                {{ number_format($book->product?->price ?? 0, 2, ',', ' ') }} €
                            </p>
                        </div>
                    </div>
                @empty
                    <p>Žiadne knihy na zobrazenie.</p>
                @endforelse
            </div>

            <button class="carousel-control-next" type="button" onclick="scrollSlider(this, 1)">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <section class="container page-container-card mt-5">
        <h2>Pripravujeme</h2>
        <div class="slider-wrapper" style="position: relative;">
            <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <div class="book-slider">
                @forelse($upcomingBooks as $book)
                    <div class="cardd">
                        <div class="card-img-placeholder">
                            <img
                                src="{{ asset('images/placeholder.webp') }}"
                                class="cardd-img"
                                alt="{{ $book->product?->name ?? 'Kniha' }}"
                            >
                        </div>

                        <div class="cardd-body">
                            <h6 class="cardd-body-title">
                                {{ $book->product?->name ?? 'Bez názvu' }}
                            </h6>

                            <p class="cardd-body-author">
                                {{ $book->authors->pluck('full_name')->join(', ') }}
                            </p>

                            <p class="cardd-body-price">
                                {{ number_format($book->product?->price ?? 0, 2, ',', ' ') }} €
                            </p>
                        </div>
                    </div>
                @empty
                    <p>Žiadne pripravované knihy na zobrazenie.</p>
                @endforelse
            </div>

            <button class="carousel-control-next" type="button" onclick="scrollSlider(this, 1)">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    
    <section class="container page-container-card">
        <div class="MainCitat">
            <cite>
                "Najkrajšie v živote nie sú momenty, ale veci."
            </cite>
            <p>Múdry človek</p>
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