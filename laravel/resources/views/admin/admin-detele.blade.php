@extends('layouts.admin')

@section('title', 'Vymazať produkt - Admin')

@section('admin_content')
    <h1 class="section-title">Vymazať produkt</h1>

    {{-- Vyhladavacia cast --}}
    <div class="form-grid">
        <div class="form-group full-width">
            <label for="hladat">Názov knihy na vymazanie</label>
            <input type="text" id="hladat" class="form-control" placeholder="Napr. Malý princ">
        </div>
        <div class="form-actions admin-vyhladat-button">
             <button class="btn-dark-custom">Vyhľadať produkt</button>
        </div>
    </div>

    <hr class="mb-5">

    {{-- Potvrdzovacia cast s--}}
    <div class="form-grid">
        <div class="form-group full-width">
            <h2 class="text-danger-title" style="color: #dc3545;">Naozaj chcete vymazať tento produkt?</h2>
            
            <div class="delete-confirm-box" style="display: flex; gap: 20px; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                <div class="delete-confirm-image">
                    {{-- Tu bude neskôr dynamický obrázok --}}
                    <img src="{{ asset('images/search.png') }}" alt="Produkt" width="100">
                </div>
                <div class="delete-confirm-info">
                    <h5>Malý princ</h5>
                    <p>Antoine de Saint-Exupéry</p>
                    <p class="delete-confirm-details" style="font-size: 0.9rem; color: #666;">
                        <strong>Cena:</strong> 9,99 € | <strong>ISBN:</strong> 978-80-00-000
                    </p>
                </div>
            </div>
        </div>

        <div class="form-group full-width">
            <p class="text-muted-custom">Pozor: Vymazaním sa produkt natrvalo odstráni z e-shopu aj zo všetkých kategórií. Túto akciu nie je možné vrátiť späť.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-dark-custom btn-danger" style="background-color: #dc3545; border-color: #dc3545;">
                Áno, vymazať produkt
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-dark-custom">Zrušiť</a>
        </div>
    </div>
@endsection