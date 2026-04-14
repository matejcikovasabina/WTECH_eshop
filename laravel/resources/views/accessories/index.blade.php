@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a>
            <span>&gt;</span>
            <span class="active">Knižné doplnky</span>
        </div>

        <div class="container-fluid">
            <div class="main-layout">
                <aside class="sidebar-filters">
                    <h6>Filtre</h6>
                    <hr>

                    <form action="{{ route('accessories.index') }}" method="GET" id="filter-form">
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
                        <h5>Všetky doplnky</h5>
                        <div class="ms-auto">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="ms-2">Najnovšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'cheapest']) }}" class="ms-2">Najlacnejšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'most_expensive']) }}" class="ms-2">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        // Používame premennú $accessories, ktorú posielame z AccessoryController
                        $itemsArray = $accessories->items();
                        $chunks = array_chunk($itemsArray, 3);
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $accessory)
                                <div class="col">
                                    <div class="card overview-card">
                                        <div class="book-cover-place">
                                            <img 
                                                src="{{ asset('images/' . ($accessory->image_path ?? 'default_accessory.webp')) }}" 
                                                class="card-img-top" 
                                                alt="{{ $accessory->name }}"
                                            >
                                        </div>

                                        <div class="card-body">
                                            <h6 class="card-title">{{ $accessory->name }}</h6>
                                            <p class="card-text">
                                                <small>{{ \Illuminate\Support\Str::limit($accessory->description ?? '', 80, '...') }}</small>
                                            </p>
                                        </div>

                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span>
                                                {{ number_format($accessory->price ?? 0, 2, ',', ' ') }} €
                                            </span>
                                            <button class="btn btn-dark btn-sm" type="button" onclick="addToCart({{ $accessory->product_id }})">
                                                Košík
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>Momentálne nemáme žiadne doplnky v ponuke.</p>
                    @endforelse

                    <div class="pagination-wrapper">
                        <nav class="pages-number-box">
                            {{ $accessories->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
@endsection