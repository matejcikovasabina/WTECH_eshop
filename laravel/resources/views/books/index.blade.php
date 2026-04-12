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

                    <form action="{{ route('books.index') }}" method="GET" id="filter-form">
                        <div class="filter-section">
                            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#collapseLanguage">
                                JAZYK <span class="arrow-icon">▾</span>
                            </h6>
                            <div id="collapseLanguage" class="collapse show">
                                <div class="filter-content">
                                    @foreach(['Slovenčina', 'Čeština', 'Angličtina'] as $lang)
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                type="checkbox" 
                                                name="language[]" {{-- MUSÍ BYŤ POLE --}}
                                                value="{{ $lang }}" 
                                                id="lang-{{ $loop->index }}"
                                                {{-- Tento riadok zabezpečí, že checkbox ostane zakliknutý aj po obnovení stránky --}}
                                                {{ is_array(request('language')) && in_array($lang, request('language')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label" for="lang-{{ $loop->index }}">
                                                {{ $lang }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        {{-- Ak nechceš onchange="this.form.submit()", pridaj sem tlačidlo: --}}
                        {{-- <button type="submit" class="btn btn-primary btn-sm mt-3">Filtrovať</button> --}}

                        <hr>

                        <div class="filter-section">
                            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#collapsePublisher">
                                Vydavateľstvo <span class="arrow-icon">▾</span>
                            </h6>
                            <div class="collapse show" id="collapsePublisher">
                                <div class="filter-content">
                                    @foreach(['Ikar', 'Slovart', 'Plus'] as $pub)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="publisher[]" 
                                                value="{{ $pub }}" id="pub-{{ $loop->index }}"
                                                {{ is_array(request('publisher')) && in_array($pub, request('publisher')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label" for="pub-{{ $loop->index }}">
                                                {{ $pub }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#collapseBinding">
                                Väzba <span class="arrow-icon">▾</span>
                            </h6>
                            <div class="collapse show" id="collapseBinding"> {{-- pridal som 'show' aby to bolo vidno --}}
                                <div class="filter-content">
                                    @foreach(['Brožovaná', 'Pevná väzba', 'E-kniha'] as $bind)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="cover_type[]" 
                                                value="{{ $bind }}" id="bind-{{ $loop->index }}"
                                                {{ is_array(request('cover_type')) && in_array($bind, request('cover_type')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label" for="bind-{{ $loop->index }}">{{ $bind }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#collapseRating">
                                Hodnotenia <span class="arrow-icon">▾</span>
                            </h6>
                            <div class="collapse show" id="collapseRating">
                                <div class="filter-content">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="rating[]" 
                                                value="{{ $i }}" id="rate-{{ $i }}"
                                                {{ is_array(request('rating')) && in_array((string)$i, request('rating')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label" for="rate-{{ $i }}">
                                                @for($j = 1; $j <= 5; $j++)
                                                    {{ $j <= $i ? '★' : '☆' }}
                                                @endfor
                                                ({{ $i }}+)
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                    </form>

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
                                                    / {{ $book->language ?? 'sk' }} / {{ $book->cover_type ?? 'brožovaná' }}
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