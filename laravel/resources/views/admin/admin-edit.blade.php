@extends('layouts.admin')

@section('title', 'Editovať produkt - Admin')

@section('admin_content')
    <h1 class="section-title">Editovať produkt</h1>

    {{-- 1. VYHLADAVACIA SEKCIA --}}
    <form action="{{ route('admin.products.edit_search') }}" method="GET">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="hladat">Vyhľadať produkt podľa názvu</label>
                <input type="text" name="query" id="hladat" class="form-control" 
                       placeholder="Zadajte názov na úpravu..." value="{{ request('query') }}">
            </div>
            <div class="form-actions admin-vyhladat-button">
                <button type="submit" class="btn-dark-custom">Vyhľadať</button>
            </div>
        </div>
    </form>

    <hr class="mb-5">

    {{-- 2. Formular pre upravu dat --}}
    @if(isset($product))
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                {{-- SPOLOCNE POLIA--}}
                <div class="form-group">
                    <label for="e-nazov">Názov produktu *</label>
                    <input type="text" id="e-nazov" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="e-cena">Cena (€) *</label>
                    <input type="number" step="0.01" id="e-cena" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="form-group">
                    <label for="pocetnasklade">Počet na sklade *</label>
                    <input type="number" id="pocetnasklade" name="stock_count" class="form-control" value="{{ old('stock_count', $product->stock_count) }}" required>
                </div>
                <div class="form-group">
                    <label for="kategoria">Kategória *</label>
                    <select id="kategoria" name="category_id" class="form-control" required>
                        <option value="">-- Vyberte kategóriu --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="product_type">Typ produktu</label>
                    <select id="product_type" name="type" class="form-control" disabled>
                        <option value="book" {{ $product->type == 'book' ? 'selected' : '' }}>Kniha</option>
                        <option value="giftcard" {{ $product->type == 'giftcard' ? 'selected' : '' }}>Darčeková poukážka</option>
                        <option value="accessory" {{ $product->type == 'accessory' ? 'selected' : '' }}>Príslušenstvo</option>
                    </select>
                    <input type="hidden" name="type" value="{{ $product->type }}">
                </div>

                <div class="form-group full-width">
                    <label for="e-opis">Opis produktu *</label>
                    <textarea id="e-opis" name="description" class="form-control" rows="5" required>{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- SEKCIA PRE KNIHU --}}
                <div id="section-book" class="type-section full-width" style="display: none;">
                    <div class="form-grid" style="margin-top: 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                        <div class="form-group full-width">
                            <label for="authors_text">Autori (oddeľte čiarkou) *</label>
                            <input type="text" id="authors_text" name="authors_raw" 
                                   value="{{ old('authors_raw', $product->book ? $product->book->authors->map(function($a){ return $a->first_name . ' ' . $a->last_name; })->implode(', ') : '') }}">
                        </div>
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $product->book->isbn ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="rok">Rok vydania</label>
                            <input type="number" name="year" value="{{ old('year', $product->book->year ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="jazyk">Jazyk</label>
                            <select name="language_id" class="form-control">
                                <option value="">-- Vyberte --</option>
                                @foreach($languages as $lang) 
                                    <option value="{{ $lang->id }}" {{ (isset($product->book) && $product->book->language_id == $lang->id) ? 'selected' : '' }}>{{ $lang->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vydavatel">Vydavateľstvo</label>
                            <select name="publisher_id" class="form-control">
                                <option value="">-- Vyberte --</option>
                                @foreach($publishers as $pub) 
                                    <option value="{{ $pub->id }}" {{ (isset($product->book) && $product->book->publisher_id == $pub->id) ? 'selected' : '' }}>{{ $pub->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="binding_id">Väzba</label>
                            <select name="binding_id" class="form-control">
                                <option value="">-- Vyberte --</option>
                                @foreach($bindings as $bind) 
                                    <option value="{{ $bind->id }}" {{ (isset($product->book) && $product->book->binding_id == $bind->id) ? 'selected' : '' }}>
                                        {{ $bind->name }}
                                    </option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="strany">Počet strán</label>
                            <input type="number" name="pages_num" value="{{ old('pages_num', $product->book->pages_num ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="hmotnost">Hmotnosť (g)</label>
                            <input type="number" name="weight" value="{{ old('weight', $product->book->weight ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="sirka">Šírka (mm)</label>
                            <input type="number" step="0.1" name="width" value="{{ old('width', $product->book->width ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="vyska">Výška (mm)</label>
                            <input type="number" step="0.1" name="height" value="{{ old('height', $product->book->height ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="hlbka">Hĺbka (mm)</label>
                            <input type="number" step="0.1" name="depth" value="{{ old('depth', $product->book->depth ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- SEKCIA PRE POUKAZKU --}}
                <div id="section-giftcard" class="type-section full-width" style="display: none;">
                    <div class="form-grid" style="margin-top: 0; background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                        <div class="form-group">
                            <label for="hodnota">Hodnota poukážky (€)</label>
                            <input type="number" name="value" value="{{ old('value', $product->giftcard->value ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="kod">Kód poukážky</label>
                            <input type="text" name="code" value="{{ old('code', $product->giftcard->code ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- OBRAZKY --}}
                <div class="form-group full-width">
                    <label>Aktuálne fotografie</label>
                    <div class="d-flex gap-2 mb-3">
                        @foreach($product->images as $image)
                            <img src="{{ asset($image->image_path) }}" width="80" height="100" style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                        @endforeach
                    </div>
                    <label for="e-foto">Nahradiť fotografie (staré sa zmažú)</label>
                    <input type="file" id="e-foto" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-dark-custom">Uložiť zmeny</button>
                <a href="{{ route('admin.products.edit_search') }}" class="btn-dark-custom">Zrušiť</a>
            </div>
        </form>
    @elseif(request('query'))
        <div class="alert alert-warning mt-4">Produkt s názvom "{{ request('query') }}" nebol nájdený.</div>
    @endif

    <script>
        function toggleSections() {
            const type = document.getElementById('product_type').value;
            document.querySelectorAll('.type-section').forEach(el => el.style.display = 'none');
            
            if(type === 'book') {
                const section = document.getElementById('section-book');
                if(section) section.style.display = 'block';
            }
            if(type === 'giftcard') {
                const section = document.getElementById('section-giftcard');
                if(section) section.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleSections();
        });
    </script>
@endsection