@extends('layouts.admin')

@section('title', 'Admin Panel - Domov')

@section('admin_content')
<div class="admin-container" style="padding: 20px;">
    <div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="section-title">Zoznam produktov</h2>
        <a href="{{ route('admin.products.create') }}" class="btn-add">+ Pridať produkt</a>
    </div>

    {{-- SEARCH BAR --}}
    <form action="{{ route('admin.products.index') }}" method="GET">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="search">Vyhľadať produkt</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Hľadať podľa názvu alebo ISBN..." value="{{ request('search') }}">
            </div>
            <div class="form-actions admin-vyhladat-button" style="display: flex; gap: 10px; align-items: flex-end;">
                 <button type="submit" class="btn-dark-custom">Vyhľadať</button>
                 @if(request('search'))
                    <a href="{{ route('admin.products.index') }}" class="btn-dark-custom" style="background: #6c757d;">Zrušiť</a>
                 @endif
            </div>
        </div>
    </form>

    <hr class="mb-5">

    <table class="table admin-table-fixed">
        <thead>
            <tr>
                <th style="width: 80px;">Foto</th>
                <th>Názov a Autor</th>
                <th style="width: 120px;">Typ</th>
                <th style="width: 100px;">Cena</th>
                <th style="width: 100px;">Sklad</th>
                <th style="width: 200px; text-align: center;">Akcie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="td-center">
                    <div class="product-img-container">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset($product->images->first()->image_path) }}">
                        @else
                            <div class="img-placeholder">NA</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="product-info-cell">
                        <strong>{{ $product->name }}</strong><br>
                        <small class="text-muted">
                            @if($product->book && $product->book->authors->isNotEmpty())
                                {{ $product->book->authors->pluck('full_name')->implode(', ') }}
                            @else
                                {{ $product->category->name ?? 'Bez kategórie' }}
                            @endif
                        </small>
                    </div>
                </td>
                <td><span class="badge badge-{{ strtolower($product->type) }}">{{ ucfirst($product->type) }}</span></td>
                <td><strong>{{ number_format($product->price, 2) }} €</strong></td>
                <td>{{ $product->stock_count }} ks</td>
                <td>
                    <div class="product-actions" style="justify-content: center;">
                        <a href="{{ route('admin.products.edit_search', ['query' => $product->name]) }}" class="btn-edit">Upraviť</a>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Naozaj zmazať?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Zmazať</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection