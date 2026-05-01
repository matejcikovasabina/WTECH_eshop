@extends('layouts.admin')

@section('title', 'Vymazať produkt - Admin')

@section('admin_content')
    <h1 class="section-title">Vymazať produkt</h1>

    {{-- 1. VYHLADAVACIA CAST --}}
    <form action="{{ route('admin.products.delete_search') }}" method="GET">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="hladat">Názov knihy na vymazanie</label>
                <input type="text" name="query" id="hladat" class="form-control" 
                       placeholder="Napr. Malý princ" value="{{ request('query') }}">
            </div>
            <div class="form-actions admin-vyhladat-button">
                 <button type="submit" class="btn-dark-custom">Vyhľadať produkt</button>
            </div>
        </div>
    </form>

    <hr class="mb-5">

    {{-- 2. PORVRDZOVACIA CAST--}}
    @if(isset($product))
        <div class="form-grid">
            <div class="form-group full-width">
                <h2 class="text-danger-title" style="color: #dc3545;">Naozaj chcete vymazať tento produkt?</h2>
                
                <div class="delete-confirm-box" style="display: flex; gap: 20px; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                    <div class="delete-confirm-image">
                        @php $mainImage = $product->images->first()?->image_path; @endphp
                        <img src="{{ $mainImage ? asset($mainImage) : asset('images/no-image.webp') }}" alt="Produkt" width="100">
                    </div>
                    <div class="delete-confirm-info">
                        <h5>{{ $product->name }}</h5>
                        <p>
                            @if($product->book)
                                {{ $product->book->authors->pluck('full_name')->implode(', ') }}
                            @else
                                {{ ucfirst($product->type) }}
                            @endif
                        </p>
                        <p class="delete-confirm-details" style="font-size: 0.9rem; color: #666;">
                            <strong>Cena:</strong> {{ number_format($product->price, 2) }} € 
                            @if($product->book)
                                | <strong>ISBN:</strong> {{ $product->book->isbn }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <p class="text-muted-custom">Pozor: Vymazaním sa produkt natrvalo odstráni z e-shopu. Túto akciu nie je možné vrátiť späť.</p>
            </div>

            <div class="form-actions">
                {{-- MAZACI FORM --}}
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-dark-custom btn-danger" style="background-color: #dc3545; border-color: #dc3545;">
                        Áno, vymazať produkt
                    </button>
                </form>
                <a href="{{ route('admin.products.delete_search') }}" class="btn-dark-custom">Zrušiť</a>
            </div>
        </div>
    @elseif(request('query'))
        <div class="alert alert-warning">Produkt s názvom "{{ request('query') }}" sa nenašiel.</div>
    @endif
@endsection