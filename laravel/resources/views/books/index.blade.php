@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="/">HOME</a>
            <span>&gt;</span>
            <a href="/knihy">Knihy</a>
            <span>&gt;</span>
            <span>Beletria</span>
        </div>

        <div class="container-fluid">
            <div class="main-layout">
                <aside class="sidebar-filters">
                    <h6>Filtre</h6>
                    <hr>

                    <div class="filter-section">
                        <h6 class="filter-title" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapseLanguage" 
                            role="button" 
                            aria-expanded="true">
                            Jazyk
                            <span class="arrow-icon">▾</span>
                        </h6>
                        <div class="collapse show" id="collapseLanguage">
                            <div class="filter-content">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lang1" checked>
                                    <label class="form-check-label" for="lang1">slovenčina</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lang2">
                                    <label class="form-check-label" for="lang2">čeština</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lang3">
                                    <label class="form-check-label" for="lang3">angličtina</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lang4">
                                    <label class="form-check-label" for="lang4">ine</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="filter-section">
                        <h6 class="filter-title" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapsePublisher" 
                            role="button" 
                            aria-expanded="false">
                            Vydavateľstvo
                            <span class="arrow-icon">▾</span>
                        </h6>
                        <div class="collapse" id="collapsePublisher">
                            <div class="filter-content">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pub1">
                                    <label class="form-check-label" for="pub1">Ikar (124)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pub2">
                                    <label class="form-check-label" for="pub2">Slovart (89)</label>
                                </div>
                                <a href="#" class="small text-muted d-block mt-2">+ Zobraziť viac</a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="filter-section">
                        <h6 class="filter-title" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapseBinding" 
                            role="button" 
                            aria-expanded="false">
                            Väzba
                            <span class="arrow-icon">▾</span>
                        </h6>
                        <div class="collapse" id="collapseBinding">
                            <div class="filter-content">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vazba1">
                                    <label class="form-check-label" for="vazba1">Brožovaná</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vazba2">
                                    <label class="form-check-label" for="vazba2">Pevná väzba</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vazba3">
                                    <label class="form-check-label" for="vazba3">E-kniha</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="filter-section">
                        <h6 class="filter-title" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapseRating" 
                            role="button" 
                            aria-expanded="false">
                            Hodnotenia
                            <span class="arrow-icon">▾</span>
                        </h6>
                        <div class="collapse" id="collapseRating">
                            <div class="filter-content">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rate5">
                                    <label class="form-check-label" for="rate5">★★★★★ (5)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rate4">
                                    <label class="form-check-label" for="rate4">4★ a viac</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rate3">
                                    <label class="form-check-label" for="rate3">3★ a viac</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rate2">
                                    <label class="form-check-label" for="rate2">2★ a viac</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rate1">
                                    <label class="form-check-label" for="rate1">1★ a viac</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                </aside>

                <section class="main-content">

                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#">Trilery</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Horory</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Detektívky</a></li>
                    </ul>

                    <div class="sortby-bar">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="skladom">
                            <label class="form-check-label" for="skladom">Iba skladom</label>
                        </div>
                        <div>
                            <a href="#">Bestsellers</a>
                            <a href="#">Najnovšie</a>
                            <a href="#">Najlacnejšie</a>
                            <a href="#">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        $booksArray = $books->items();
                        $chunks = array_chunk($booksArray, 3);
                    @endphp

                    @foreach($chunks as $chunk)
                        <!-- Book cards – row -->
                        <div class="row custom-book-grid">
                            @foreach($chunk as $book)
                                <div class="col">
                                    <div class="card overview-card">
                                        <div class="book-cover-place">
                                            <img 
                                                src="{{ asset('images/' . ($book->cover_image ?? 'adults.webp')) }}" 
                                                class="card-img-top" 
                                                alt="{{ $book->title }}"
                                            >
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $book->title }}</h6>
                                            <p class="card-text mb-1"><small>{{ $book->author->name ?? 'Autor' }}</small></p>
                                            <p class="card-text mb-1">
                                                <small>
                                                    @php
                                                        $rating = $book->rating ?? 0;
                                                        $fullStars = floor($rating);
                                                        $hasHalf = ($rating - $fullStars) >= 0.5;
                                                    @endphp
                                                    @for ($i = 0; $i < $fullStars; $i++)★@endfor@if($hasHalf)☆@endif@for ($i = $fullStars + ($hasHalf ? 1 : 0); $i < 5; $i++)☆@endfor
                                                    / {{ $book->language ?? 'sk' }} / {{ $book->binding ?? 'brožovaná' }}
                                                </small>
                                            </p>
                                            <p class="card-text"><small>{{ Str::limit($book->description, 80, '...') }}</small></p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span>{{ number_format($book->price, 2, ',', ' ') }} €</span>
                                            <button class="btn btn-dark btn-sm" onclick="addToCart({{ $book->id }})">Košík</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <!-- Paginacia -->
                    <div class="pagination-wrapper">
                        <nav class="pages-number-box">
                            {{ $books->links('pagination::bootstrap-4') }}
                        </nav>
                        @if($books->hasMorePages())
                            <a href="{{ $books->nextPageUrl() }}" class="btn btn-dark">Next</a>
                        @endif
                    </div>

                </section>
            </div>
        </div>
    </section>
</main>
@endsection