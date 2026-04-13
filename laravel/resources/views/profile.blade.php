@extends('layouts.auth')

@section('title', 'Profil')

@section('content')
<main class="py-5">
    <section class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="profile-card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="text-center mb-4">Profil</h1>

                        <div class="text-center mb-4">
                            <div class="profile-avatar">
                                <span>Avatar</span>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="first_name" class="form-label">Meno</label>
                                    <input 
                                        type="text" 
                                        id="first_name"
                                        name="first_name" 
                                        class="form-control" 
                                        value="{{ old('first_name', auth()->user()->first_name) }}"
                                        placeholder="Zadajte meno"
                                    >
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="last_name" class="form-label">Priezvisko</label>
                                    <input 
                                        type="text" 
                                        id="last_name"
                                        name="last_name" 
                                        class="form-control" 
                                        value="{{ old('last_name', auth()->user()->last_name) }}"
                                        placeholder="Zadajte priezvisko"
                                    >
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        id="email"
                                        name="email" 
                                        class="form-control" 
                                        value="{{ old('email', auth()->user()->email) }}"
                                        placeholder="Zadajte email"
                                    >
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-center">
                                <button type="submit" class="btn btn-dark">
                                    Uložiť zmeny
                                </button>
                            </div>
                        </form>

                        @if(auth()->user()->role_id == 1)
                            <div class="text-center mt-3">
                                <a href="/admin" class="btn btn-outline-dark admin-btn">
                                    prejsť do ADMIN časti
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection