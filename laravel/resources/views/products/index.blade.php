@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        {{-- 1. DYNAMICKY BREADCRUMB --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a>
            <span>&gt;</span>
            <span class="active">
                @if($type === 'book') Knihy 
                @elseif($type === 'giftcard') Darčekové poukážky 
                @else Knižné doplnky 
                @endif
            </span>
        </div>

        <div class="container-fluid">
            <div class="main-layout">
                {{-- SIDEBAR FILTRE --}}
                <aside class="sidebar-filters">
                    <h6>Filtre</h6>
                    <hr>
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
                    <hr>
                </aside>

                <section class="main-content">
                    <div class="sortby-bar">
                        <h5>
                            @if($type === 'book') Všetky knihy 
                            @elseif($type === 'giftcard') Darčekové poukážky 
                            @else Všetky doplnky 
                            @endif
                        </h5>
                        <div class="ms-auto">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="ms-2 text-decoration-none">Najnovšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'cheapest']) }}" class="ms-2 text-decoration-none">Najlacnejšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'most_expensive']) }}" class="ms-2 text-decoration-none">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        $chunks = $products->chunk(3);
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $product)
                                <div class="col">
                                    <div class="card overview-card h-100">
                                        {{-- ODKAZ NA OBRÁZKU --}}
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <div class="book-cover-place">
                                                {{-- NACITANIE OBRAZKA --}}
                                                @php
                                                    $imagePath = $product->images->first()?->image_path;

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
                                                    {{-- ak je to kniha, vypiseme autora -> inak limitovany popis --}}
                                                    @if($type === 'book')
                                                        {{ $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                                                    @else
                                                        {{ \Illuminate\Support\Str::limit($product->{$type}->description ?? '', 80, '...') }}
                                                    @endif
                                                </small>
                                            </p>
                                        </div>

                                        <div class="card-footer d-flex justify-content-between align-items-center bg-white border-top-0">
                                            <span class="fw-bold">
                                                {{ number_format($product->price ?? 0, 2, ',', ' ') }} €
                                            </span>
                                            {{-- BUTTON KOSIK --}}
                                            <button class="btn btn-dark btn-sm" type="button" onclick="addToCart({{ $product->id }})">
                                                Košík
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="text-center mt-5">Momentálne nemáme žiadne produkty v tejto kategórii.</p>
                    @endforelse

                    {{-- PAGINACIA --}}
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