@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        {{-- 1. DYNAMICKÝ BREADCRUMB --}}
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
                    {{-- Dynamická route pre form podľa typu --}}
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
                        // Laravel paginácia vracia kolekciu objektov, chunkujeme ju po 3
                        $chunks = $products->chunk(3);
                        
                        // Určíme prefix pre routy (books.show, accessories.show, giftcards.show)
                        $routePrefix = ($type === 'book') ? 'books' : (($type === 'giftcard') ? 'giftcards' : 'accessories');
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $product)
                                <div class="col">
                                    <div class="card overview-card h-100">
                                        {{-- ODKAZ NA OBRÁZKU --}}
                                        <a href="{{ route($routePrefix . '.show', $product->id) }}">
                                            <div class="book-cover-place">
                                                {{-- Načítame obrázok z tabuľky book/accessory/giftcard cez reláciu --}}
                                                @php
                                                    $imagePath = $product->{$type}->image_path ?? 'default_accessory.webp';
                                                @endphp
                                                <img 
                                                    src="{{ asset('images/' . $imagePath) }}" 
                                                    class="card-img-top" 
                                                    alt="{{ $product->name }}"
                                                >
                                            </div>
                                        </a>

                                        <div class="card-body">
                                            {{-- ODKAZ NA NÁZVE --}}
                                            <h6 class="card-title">
                                                <a href="{{ route($routePrefix . '.show', $product->id) }}" class="text-decoration-none text-dark">
                                                    {{ $product->name }}
                                                </a>
                                            </h6>
                                            
                                            <p class="card-text">
                                                <small>
                                                    {{-- Ak je to kniha, vypíšeme autora, inak limitovaný popis --}}
                                                    @if($type === 'book')
                                                        {{ $product->book->authors ?? '' }}
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
                                            {{-- BUTTON KOŠÍK - Identický pre všetky typy --}}
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

                    {{-- UNIVERZÁLNA PAGINÁCIA --}}
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