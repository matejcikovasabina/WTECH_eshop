@extends('layouts.app')

@section('title', 'Admin Panel - ReadIt')

@section('content')
<main class="background">
    <section class="page-section">
        <div class="admin-layout container">
            
            {{-- SIDEBAR --}}
            <div class="admin-card admin-sidebar">
                <h2 class="section-title">Admin panel</h2>
                
                <a href="{{ route('admin.products.create') }}" class="btn-dark-custom mb-2 d-block text-decoration-none">
                    <i class="bi bi-plus-circle me-2"></i>Pridať produkt
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="btn-dark-custom mb-2 d-block text-decoration-none">
                    <i class="bi bi-pencil-square me-2"></i>Zoznam a Editácia
                </a>
                
                <a href="{{ route('admin.products.delete-page') }}" class="btn-dark-custom mb-2 d-block text-decoration-none">
                    <i class="bi bi-trash me-2"></i>Vymazať produkt
                </a>
                
                <hr class="my-4">
                
                <a href="{{ route('profile') }}" class="btn-dark-custom d-block text-decoration-none">
                    <i class="bi bi-arrow-left me-2"></i>Späť na profil
                </a>
            </div>

            {{-- HLAVNY OBSAH--}}
            <div class="admin-card admin-main-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('admin_content')
            </div>

        </div>
    </section>
</main>
@endsection