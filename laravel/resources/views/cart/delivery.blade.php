@extends('layouts.app')

@section('content')

@include('partials.checkout-steps', ['activeStep' => 'delivery'])

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center g-4">

            <div class="col-12 col-lg-4">
                <div class="sticky-top cart-sticky">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-4">Košík</h2>

                            @forelse($cart as $item)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-muted small">{{ $item['quantity'] }} ks</span>
                                    <strong>
                                        {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                    </strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Košík je prázdny.</p>
                            @endforelse

                            <div class="d-flex justify-content-between align-items-center pt-4 fs-5 fw-bold">
                                <span>Spolu</span>
                                <strong>{{ number_format($total, 2, ',', ' ') }} €</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card address-card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="{{ route('cart.delivery.store') }}">
                            @csrf

                            <h2 class="h4 mb-2">Spôsob doručenia</h2>
                            <p class="text-muted mb-4">Vyber si spôsob, ktorý ti najviac vyhovuje.</p>

                            <div class="d-flex flex-column gap-3 mb-4">

                                <label class="border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="delivery" id="pickup" value="pickup" checked>
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Osobný odber na predajni</h3>
                                                    <p class="text-muted mb-2">Vyzdvihnutie pripravené už do 24 hodín</p>
                                                </div>
                                                <span class="badge text-bg-success fs-6 align-self-start">Zdarma</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Najbližšia predajňa: Bratislava Centrum</span>
                                                <span>Dostupnosť: zajtra</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="delivery" id="courier" value="courier">
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Kuriér na adresu</h3>
                                                    <p class="text-muted mb-2">Doručenie priamo domov alebo do práce</p>
                                                </div>
                                                <span class="badge text-bg-secondary fs-6 align-self-start">3.90€</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Odhad doručenia: 1–2 pracovné dni</span>
                                                <span>Bezpečne zabalené</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="border rounded-4 p-3 p-md-4 bg-white">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="delivery" id="packeta" value="packeta">
                                        <div class="ms-4">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                                <div>
                                                    <h3 class="h5 mb-1">Packeta / výdajné miesto</h3>
                                                    <p class="text-muted mb-2">Pohodlné vyzdvihnutie, keď ti to vyhovuje</p>
                                                </div>
                                                <span class="badge text-bg-secondary fs-6 align-self-start">2.49€</span>
                                            </div>
                                            <div class="small text-muted d-flex flex-column flex-md-row gap-2 gap-md-4">
                                                <span>Viac než 2000 výdajných miest</span>
                                                <span>Uloženie zásielky až 7 dní</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="pt-2">
                                <h4 class="h5 mb-3">Fakturačné údaje</h4>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresa</label>
                                    <input 
                                        type="text" 
                                        id="address" 
                                        name="address"
                                        class="form-control" 
                                        placeholder="Zadaj ulicu a číslo domu"
                                        value="{{ old('address') }}"
                                    >
                                </div>

                                <div class="row g-2">
                                    <div class="col-12 col-md-8">
                                        <label for="city" class="form-label">Mesto</label>
                                        <input 
                                            type="text" 
                                            id="city" 
                                            name="city"
                                            class="form-control" 
                                            placeholder="Bratislava"
                                            value="{{ old('city') }}"
                                        >
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="zip" class="form-label">PSČ</label>
                                        <input 
                                            type="text" 
                                            id="zip" 
                                            name="zip"
                                            class="form-control" 
                                            placeholder="811 01"
                                            value="{{ old('zip') }}"
                                        >
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label for="note" class="form-label">Poznámka pre kuriéra</label>
                                    <textarea 
                                        id="note" 
                                        name="note"
                                        rows="4" 
                                        class="form-control" 
                                        placeholder="Napr. zazvoniť pri vchode alebo nechať balík na recepcii"
                                    >{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="continue-btn">
                                    Pokračovať
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection