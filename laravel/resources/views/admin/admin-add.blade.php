@extends('layouts.admin')

@section('admin_content')
    <h1 class="section-title">Pridať produkt</h1>

    {{-- Zobrazenie chýb --}}
    @if ($errors->any())
        <div style="background: #ffdbdb; color: #a40000; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #a40000;">
            <strong>Produkt sa nepodarilo uložiť:</strong>
            <ul style="margin-top: 5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            {{-- SPOLOCNÉ POLIA (TabuLka products) --}}
            <div class="form-group">
                <label for="nazov">Názov produktu *</label>
                <input type="text" id="nazov" name="name" value="{{ old('name') }}" placeholder="Napr. Malý princ" required>
            </div>

            <div class="form-group">
                <label for="cena">Cena (€) *</label>
                <input type="number" step="0.01" id="cena" name="price" value="{{ old('price') }}" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label for="pocetnasklade">Počet na sklade *</label>
                <input type="number" id="pocetnasklade" name="stock_count" value="{{ old('stock_count') }}" required>
            </div>

            <div class="form-group">
                <label for="kategoria">Kategória *</label>
                <select id="kategoria" name="category_id" required>
                    <option value="">-- Vyberte kategóriu --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="product_type">Typ produktu *</label>
                <select id="product_type" name="type" onchange="toggleSections()" required>
                    <option value="">-- Vyberte typ --</option>
                    <option value="book" {{ old('type') == 'book' ? 'selected' : '' }}>Kniha</option>
                    <option value="giftcard" {{ old('type') == 'giftcard' ? 'selected' : '' }}>Darčeková poukážka</option>
                    <option value="accessory" {{ old('type') == 'accessory' ? 'selected' : '' }}>Príslušenstvo</option>
                </select>
            </div>

            {{-- SPOLOCNY OPIS --}}
            <div class="form-group full-width">
                <label for="opis">Opis produktu *</label>
                <textarea id="opis" name="description" rows="5" placeholder="Napíšte opis produktu…" required>{{ old('description') }}</textarea>
            </div>

            {{-- SEKCIA PRE KNIHU --}}
            <div id="section-book" class="type-section full-width" style="display: none;">
                <div class="form-grid" style="margin-top: 0;">
                    <div class="form-group full-width">
                        <label for="authors_text">Autori (oddeľte čiarkou) *</label>
                        <input type="text" id="authors_text" name="authors_raw" value="{{ old('authors_raw') }}" placeholder="Meno Priezvisko, Iný Autor, ...">
                        <small class="text-muted">Príklad: Pavol Dobšinský, J.K. Rowling</small>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}">
                    </div>
                    <div class="form-group">
                        <label for="rok">Rok vydania</label>
                        <input type="number" id="rok" name="year" value="{{ old('year') }}" placeholder="2024">
                    </div>
                    <div class="form-group">
                        <label for="jazyk">Jazyk</label>
                        <select name="language_id">
                            <option value="">-- Vyberte --</option>
                            @foreach($languages as $lang) <option value="{{ $lang->id }}">{{ $lang->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vydavatel">Vydavateľstvo</label>
                        <select name="publisher_id">
                            <option value="">-- Vyberte --</option>
                            @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vazba">Väzba</label>
                        <select name="binding_id">
                            <option value="">-- Vyberte --</option>
                            @foreach($bindings as $bind) <option value="{{ $bind->id }}">{{ $bind->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hmotnost">Hmotnosť (g)</label>
                        <input type="number" id="hmotnost" name="weight" value="{{ old('weight') }}">
                    </div>
                    <div class="form-group">
                        <label for="strany">Počet strán</label>
                        <input type="number" name="pages_num" value="{{ old('pages_num') }}">
                    </div>
                    <div class="form-group">
                        <label for="sirka">Šírka (mm)</label>
                        <input type="number" step="0.1" name="width" value="{{ old('width') }}">
                    </div>
                    <div class="form-group">
                        <label for="vyska">Výška (mm)</label>
                        <input type="number" step="0.1" name="height" value="{{ old('height') }}">
                    </div>
                    <div class="form-group">
                        <label for="hlbka">Hĺbka (mm)</label>
                        <input type="number" step="0.1" name="depth" value="{{ old('depth') }}">
                    </div>
                </div>
            </div>

            {{-- SEKCIA PRE POUKAZKU --}}
            <div id="section-giftcard" class="type-section full-width" style="display: none;">
                <div class="form-grid" style="margin-top: 0;">
                    <div class="form-group">
                        <label for="hodnota">Hodnota poukážky (€)</label>
                        <input type="number" id="hodnota" name="value" value="{{ old('value') }}">
                    </div>
                    <div class="form-group">
                        <label for="kod">Kód poukážky</label>
                        <input type="text" id="kod" name="code" value="{{ old('code') }}">
                    </div>
                </div>
            </div>

            {{-- OBRÁZKY --}}
            <div class="form-group full-width">
                <label for="foto">Fotografie produktu (môžete vybrať viacero)</label>
                <input type="file" id="foto" name="images[]" accept="image/*" multiple>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-dark-custom">Vytvoriť produkt</button>
            <a href="{{ route('admin.products.index') }}" class="btn-dark-custom">Zrušiť</a>
        </div>
    </form>

    <script>
        function toggleSections() {
            const type = document.getElementById('product_type').value;
            // SKRYJE VSETKY SPECIFIKACIE SEKCIE
            document.querySelectorAll('.type-section').forEach(el => el.style.display = 'none');
            
            // ZOBRAZI TU SPRAVNU
            if(type === 'book') document.getElementById('section-book').style.display = 'block';
            if(type === 'giftcard') document.getElementById('section-giftcard').style.display = 'block';
        }
        // Spustit pri nacitani pre pripad navratu z validacie
        window.onload = toggleSections;
    </script>
@endsection