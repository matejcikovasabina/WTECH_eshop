@extends('layouts.app')

@section('content')
<main>
    <section class="container page-container-card">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">HOME</a>
            <span>&gt;</span>
            <span class="active">Darčekové poukážky</span>
        </div>

        <div class="container-fluid">
            <div class="main-layout">
                <aside class="sidebar-filters">
                    <h6>Filtre</h6>
                    <hr>
                    <form action="{{ route('giftcards.index') }}" method="GET" id="filter-form">
                        <div class="filter-section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" id="skladom" 
                                       onchange="this.form.submit()" {{ request('in_stock') ? 'checked' : '' }}>
                                <label class="form-check-label" for="skladom">Iba skladom</label>
                            </div>
                        </div>
                    </form>
                    <hr>
                </aside>

                <section class="main-content">
                    <div class="sortby-bar">
                        <h5>Naše poukazy</h5>
                        <div class="ms-auto">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'cheapest']) }}" class="ms-2">Najlacnejšie</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'most_expensive']) }}" class="ms-2">Najdrahšie</a>
                        </div>
                    </div>

                    @php
                        $itemsArray = $giftcards->items();
                        $chunks = array_chunk($itemsArray, 3);
                    @endphp

                    @forelse($chunks as $chunk)
                        <div class="row custom-book-grid">
                            @foreach($chunk as $card)
                                <div class="col">
                                    <div class="card overview-card text-center">
                                        {{-- Pridaný odkaz na ikonu --}}
                                        <a href="{{ route('giftcards.show', $card->id) }}">
                                            <div class="book-cover-place d-flex align-items-center justify-content-center" style="background: #f8f9fa; min-height: 200px;">
                                                <i class="bi bi-gift" style="font-size: 4rem; color: #333;"></i>
                                            </div>
                                        </a>

                                        <div class="card-body">
                                            {{-- Pridaný odkaz na názov --}}
                                            <a href="{{ route('giftcards.show', $card->id) }}" style="text-decoration: none; color: inherit;">
                                                <h6 class="card-title">{{ $card->name }}</h6>
                                            </a>
                                            <p class="card-text">
                                                <small>Ideálny darček pre každého knihomoľa.</small>
                                            </p>
                                        </div>

                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">{{ number_format($card->price, 2, ',', ' ') }} €</span>
                                            <button class="btn btn-dark btn-sm" onclick="addToCart({{ $card->id }})">
                                                Kúpiť
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p>Darčekové poukážky sú momentálne vypredané.</p>
                    @endforelse

                    <div class="pagination-wrapper">
                        {{ $giftcards->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
@endsection