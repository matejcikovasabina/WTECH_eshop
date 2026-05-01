@extends('layouts.admin')

@section('title', 'Editovať produkt - Admin')

@section('admin_content')
    <h1 class="section-title">Editovať produkt</h1>

    {{-- Vyhľadávacia sekcia --}}
    <form action="#" method="GET">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="hladat">Vyhľadať knihu podľa názvu</label>
                <input type="text" id="hladat" class="form-control" placeholder="Zadajte názov knihy na úpravu...">
            </div>
            <div class="form-actions admin-vyhladat-button">
                <button type="submit" class="btn-dark-custom">Vyhľadať</button>
            </div>
        </div>
    </form>

    <hr class="mb-5">

    {{-- Formular pre upravu dat --}}
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="e-nazov">Názov knihy *</label>
                <input type="text" id="e-nazov" name="name" class="form-control" value="Malý princ" required>
            </div>

            <div class="form-group">
                <label for="e-autor">Autor</label>
                <input type="text" id="e-autor" name="author" class="form-control" value="Antoine de Saint-Exupéry">
            </div>

            <div class="form-group">
                <label for="e-vazba">Väzba</label>
                <input type="text" id="e-vazba" name="binding" class="form-control" value="Pevná">
            </div>

            <div class="form-group">
                <label for="e-rozmer">Rozmer</label>
                <input type="text" id="e-rozmer" name="dimensions" class="form-control" value="130 x 200 mm">
            </div>

            <div class="form-group">
                <label for="e-hmotnost">Hmotnosť (g)</label>
                <input type="text" id="e-hmotnost" name="weight" class="form-control" value="350g">
            </div>

            <div class="form-group">
                <label for="e-isbn">ISBN</label>
                <input type="text" id="e-isbn" name="isbn" class="form-control" value="978-80-00-00000-0">
            </div>

            <div class="form-group">
                <label for="e-rok">Rok vydania</label>
                <input type="number" id="e-rok" name="year" class="form-control" value="2023">
            </div>

            <div class="form-group">
                <label for="e-jazyk">Jazyk</label>
                <input type="text" id="e-jazyk" name="language" class="form-control" value="Slovenský">
            </div>

            <div class="form-group">
                <label for="e-vydavatel">Vydavateľstvo</label>
                <input type="text" id="e-vydavatel" name="publisher" class="form-control" value="Mladé letá">
            </div>

            <div class="form-group">
                <label for="pocetnasklade">Počet na sklade</label>
                <input type="number" id="pocetnasklade" name="stock" class="form-control" value="5">
            </div>

            <div class="form-group full-width">
                <label for="e-opis">Opis *</label>
                <textarea id="e-opis" name="description" class="form-control" rows="5" required>Príbeh o malom princovi, ktorý cestuje z planéty na planétu...</textarea>
            </div>

            <div class="form-group full-width">
                <label>Aktuálne fotografie</label>
                <div class="image-preview-list d-flex gap-2">
                    {{-- Placeholder pre obrázky, neskôr sa nahradí cyklom @foreach --}}
                    <div class="image-preview-item" style="width: 80px; height: 100px; background: #ddd; border-radius: 4px;"></div>
                    <div class="image-preview-item" style="width: 80px; height: 100px; background: #ddd; border-radius: 4px;"></div>
                    <div class="image-preview-item" style="width: 80px; height: 100px; background: #ddd; border-radius: 4px;"></div>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="e-foto">Nahradiť fotografie (vyberte nové súbory)</label>
                <input type="file" id="e-foto" name="images[]" class="form-control" accept="image/*" multiple>
            </div>
        </div>

        <div class="form-actions mt-4">
            <button type="submit" class="btn-dark-custom btn-save-changes">Uložiť zmeny</button>
            <a href="{{ route('admin.products.index') }}" class="btn-dark-custom">Zrušiť</a>
        </div>
    </form>
@endsection