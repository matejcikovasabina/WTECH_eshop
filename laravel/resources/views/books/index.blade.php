@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a>
            <span>&gt;</span>
            <a href="{{ route('books.index') }}">Knihy</a>
            <span>&gt;</span>
            <span>{{ $mainCategories->where('id', request('category'))->first()?->name ?? 'Všetky knihy' }}</span>
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
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="language[]"
                                                value="{{ $lang }}"
                                                id="lang-{{ $loop->index }}"
                                                {{ is_array(request('language')) && in_array($lang, request('language')) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                            >
                                            <label class="form-check-label" for="lang-{{ $loop->index }}">
                                                {{ $lang }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#collapsePublisher">
                                Vydavateľstvo <span class="arrow-icon">▾</span>
                            </h6>
                            <div class="collapse show" id="collapsePublisher">
                                <div class="filter-content">
                                    @foreach(['Ikar', 'Slovart', 'Plus'] as $pub)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="publisher[]"
                                                value="{{ $pub }}"
                                                id="pub-{{ $loop->index }}"
                                                {{ is_array(request('publisher')) && in_array($pub, request('publisher')) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                            >
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
                            <div class="collapse show" id="collapseBinding">
                                <div class="filter-content">
                                    @foreach(['Brožovaná väzba', 'Pevná väzba', 'E-kniha'] as $bind)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="cover_type[]"
                                                value="{{ $bind }}"
                                                id="bind-{{ $loop->index }}"
                                                {{ is_array(request('cover_type')) && in_array($bind, request('cover_type')) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                            >
                                            <label class="form-check-label" for="bind-{{ $loop->index }}">
                                                {{ $bind }}
                                            </label>
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
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="rating[]"
                                                value="{{ $i }}"
                                                id="rate-{{ $i }}"
                                                {{ is_array(request('rating')) && in_array((string) $i, request('rating')) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                            >
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
                    <h2 class="mb-3">{{ $currentMainCategory->name ?? 'Všetky knihy' }}</h2>

                    @if($subCategories->count() > 0)
                        <div class="category-filters mb-4">
                            <ul class="nav nav-pills">
                                {{-- Tlačidlo "Zobraziť všetko z {{ Hlavná kategória }}" --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request('category') == $currentMainCategory->id ? 'active' : '' }}" 
                                    href="{{ route('books.index', ['category' => $currentMainCategory->id]) }}">
                                        Všetko
                                    </a>
                                </li>

                                @foreach($subCategories as $sub)
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('category') == $sub->id ? 'active' : '' }}" 
                                        href="{{ route('books.index', ['category' => $sub->id]) }}">
                                            {{ $sub->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="sortby-bar">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="in_stock"
                                id="skladom"
                                form="filter-form"
                                onchange="this.form.submit()"
                                {{ request('in_stock') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="skladom">Iba skladom</label>
                        </div>

                        <div class="form-check d-inline-block ms-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="bestsellers"
                                id="bestseller-check"
                                form="filter-form"
                                onchange="this.form.submit()"
                                {{ request('bestsellers') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="bestseller-check">Bestsellery</label>
                        </div>

                        <div class="ms-auto">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="ms-2">Najnovšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'cheapest']) }}" class="ms-2">Najlacnejšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'most_expensive']) }}" class="ms-2">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        $booksArray = $books->items();
                        $chunks = array_chunk($booksArray, 3);
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $book)
                                <div class="col">
                                <a href="{{ route('books.show', $book->product_id) }}" class="text-decoration-none text-dark">
                                    <div class="card overview-card">
                                        <div class="book-cover-place">
                                            <img
                                                src="{{ asset('images/' . ($book->cover_image ?? 'adults.webp')) }}"
                                                class="card-img-top"
                                                alt="{{ $book->product?->name ?? 'Kniha' }}"
                                            >
                                        </div>

                                        <div class="card-body">

                                            <h6 class="card-title">
                                                {{ $book->product?->name ?? 'Bez názvu' }}
                                            </h6>

                                            <p class="card-text mb-1">
                                                <small>
                                                    {{ $book->authors->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                                                </small>
                                            </p>

                                            <p class="card-text mb-1">
                                                <small>
                                                    @php
                                                        $rating = $book->rating ?? 0;
                                                        $fullStars = floor($rating);
                                                        $hasHalf = ($rating - $fullStars) >= 0.5;
                                                    @endphp

                                                    @for ($i = 0; $i < $fullStars; $i++)
                                                        ★
                                                    @endfor

                                                    @if($hasHalf)
                                                        ☆
                                                    @endif

                                                    @for ($i = $fullStars + ($hasHalf ? 1 : 0); $i < 5; $i++)
                                                        ☆
                                                    @endfor

                                                    / {{ $book->language?->name ?? 'Neznámy jazyk' }}
                                                    / {{ $book->binding?->name ?? 'Neznáma väzba' }}
                                                </small>
                                            </p>

                                            <p class="card-text">
                                                <small>{{ \Illuminate\Support\Str::limit($book->description ?? '', 80, '...') }}</small>
                                            </p>
                                        </div>

                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span>
                                                {{ number_format($book->product?->price ?? 0, 2, ',', ' ') }} €
                                            </span>
                                            <button class="btn btn-dark btn-sm" type="button" onclick="event.preventDefault(); addToCart({{ $book->product_id }})">
                                                Košík
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>Žiadne knihy sa nenašli.</p>
                    @endforelse

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