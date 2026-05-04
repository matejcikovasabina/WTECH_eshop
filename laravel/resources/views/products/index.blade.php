@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a>
            <span>&gt;</span>

            @if($type === 'book')
                @if(isset($isNew) && $isNew)
                    <span class="active">Novinky</span>
                @else
                    <a href="{{ route('products.index') }}">Knihy</a>
                @endif
                @if(!(isset($isNew) && $isNew) && request('category'))
                    <span>&gt;</span>
                    <span>{{ $mainCategories->where('id', request('category'))->first()?->name ?? ($currentMainCategory->name ?? 'Všetky knihy') }}</span>
                @elseif(!(isset($isNew) && $isNew))
                    <span>&gt;</span>
                    <span>Všetky knihy</span>
                @endif
            @elseif($type === 'giftcard')
                <span class="active">Darčekové poukážky</span>
            @else
                <span class="active">Knižné doplnky</span>
            @endif
        </div>

        <div class="container-fluid">
            <div class="main-layout">
                <aside class="sidebar-filters">
                    <h6>Filtre</h6>
                    <hr>

                    @if($type === 'book')
                        <form action="{{ isset($isNew) && $isNew ? route('products.new') : route('products.index') }}" method="GET" id="filter-form">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif

                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

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

                            <hr>

                            <div class="filter-section">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="in_stock" 
                                        id="skladom-sidebar" 
                                        onchange="this.form.submit()"
                                        {{ request('in_stock') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="skladom-sidebar">Iba skladom</label>
                                </div>
                            </div>

                            <div class="filter-section mt-2">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="bestsellers" 
                                        id="bestseller-sidebar" 
                                        onchange="this.form.submit()"
                                        {{ request('bestsellers') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="bestseller-sidebar">Bestsellery</label>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{ url()->current() }}" method="GET" id="filter-form">
                            <div class="filter-section">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="in_stock" 
                                        id="skladom" 
                                        onchange="this.form.submit()"
                                        {{ request('in_stock') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="skladom">Iba skladom</label>
                                </div>
                            </div>
                        </form>
                    @endif

                    <hr>
                </aside>

                <section class="main-content">
                    @if($type === 'book')
                    <h2 class="mb-3">
                        {{ isset($isNew) && $isNew ? 'Novinky' : ($currentMainCategory->name ?? 'Všetky knihy') }}
                    </h2>

                        @if(isset($subCategories) && $subCategories->count() > 0)
                            <div class="category-filters mb-4">
                                <ul class="nav nav-pills">
                                    @if(isset($currentMainCategory))
                                        <li class="nav-item">
                                            <a class="nav-link {{ request('category') == $currentMainCategory->id ? 'active' : '' }}"
                                               href="{{ route(isset($isNew) && $isNew ? 'products.new' : 'products.index', array_merge(request()->except('page'), ['category' => $currentMainCategory->id])) }}">
                                                Všetko
                                            </a>
                                        </li>
                                    @endif

                                    @foreach($subCategories as $sub)
                                        <li class="nav-item">
                                            <a class="nav-link {{ request('category') == $sub->id ? 'active' : '' }}"
                                               href="{{ route(isset($isNew) && $isNew ? 'products.new' : 'products.index', array_merge(request()->except('page'), ['category' => $sub->id])) }}">
                                                {{ $sub->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    <div class="sortby-bar">
                        <h5>
                            @if($type === 'book')
                                {{ isset($isNew) && $isNew ? 'Novinky' : ($currentMainCategory->name ?? 'Všetky knihy') }}
                            @elseif($type === 'giftcard')
                                Darčekové poukážky
                            @else
                                Všetky doplnky
                            @endif
                        </h5>

                        @if($type === 'book')
                            <div class="form-check ms-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="in_stock"
                                    id="skladom-top"
                                    form="filter-form"
                                    onchange="this.form.submit()"
                                    {{ request('in_stock') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="skladom-top">Iba skladom</label>
                            </div>

                            <div class="form-check ms-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="bestsellers"
                                    id="bestseller-top"
                                    form="filter-form"
                                    onchange="this.form.submit()"
                                    {{ request('bestsellers') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="bestseller-top">Bestsellery</label>
                            </div>
                        @endif

                        <div class="ms-auto">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => null]) }}" class="ms-2 text-decoration-none">Najnovšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'cheapest', 'page' => null]) }}" class="ms-2 text-decoration-none">Najlacnejšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'most_expensive', 'page' => null]) }}" class="ms-2 text-decoration-none">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        $productsArray = $products->items();
                        $chunks = array_chunk($productsArray, 3);
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $product)
                                <div class="col">
                                    <div class="card overview-card h-100">
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <div class="book-cover-place">
                                                @php
                                                    $imagePath = $product->images?->first()?->image_path;

                                                    if (!$imagePath && $type === 'accessory') {
                                                        $imagePath = $product->accessory?->image_path;
                                                    }
                                                @endphp

                                                <img 
                                                    src="{{ $imagePath ? asset($imagePath) : asset('images/no-image.webp') }}" 
                                                    class="card-img-top" 
                                                    alt="{{ $product->name }}"
                                                >
                                            </div>
                                        </a>

                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                                    {{ $product->name }}
                                                </a>
                                            </h6>

                                            <p class="card-text">
                                                <small>
                                                    @if($type === 'book')
                                                        {{ $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                                                    @else
                                                        {{ \Illuminate\Support\Str::limit($product->{$type}->description ?? '', 80, '...') }}
                                                    @endif
                                                </small>
                                            </p>

                                            @if($type === 'book')
                                                <p class="card-text mb-1">
                                                    <small>
                                                        @php
                                                            $rating = $product->book?->rating ?? 0;
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

                                                        / {{ $product->book?->language?->name ?? 'Neznámy jazyk' }}
                                                        / {{ $product->book?->binding?->name ?? 'Neznáma väzba' }}
                                                    </small>
                                                </p>

                                                <p class="card-text">
                                                    <small>{{ \Illuminate\Support\Str::limit($product->book?->description ?? '', 80, '...') }}</small>
                                                </p>
                                            @endif
                                        </div>

                                        <div class="card-footer d-flex justify-content-between align-items-center bg-white border-top-0">
                                            <span class="fw-bold">
                                                {{ number_format($product->price ?? 0, 2, ',', ' ') }} €
                                            </span>

                                            @if($type === 'book' && ($product->stock_count ?? 0) > 0)
                                                <form action="{{ route('cart.add') }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button class="btn btn-dark btn-sm" type="submit">
                                                        Košík
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-dark btn-sm" type="button" disabled>
                                                    Košík
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="text-center mt-5">
                            @if($type === 'book')
                                Žiadne knihy sa nenašli.
                            @else
                                Momentálne nemáme žiadne produkty v tejto kategórii.
                            @endif
                        </p>
                    @endforelse

                    <div class="pagination-wrapper mt-4">
                        <nav class="pages-number-box">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
@endsection
