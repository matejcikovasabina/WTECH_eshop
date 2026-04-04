@extends('layouts.auth')

@section('title', 'Registrácia')

@section('content')
<section class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
            <div class="register-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="text-center mb-4">Registrácia</h1>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="example@gmail.com"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label">Heslo</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="••••••"
                                    required
                                    autocomplete="new-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label">Zopakujte heslo</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="••••••"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">Meno</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Mária"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="given-name"
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="surname" class="form-label">Priezvisko</label>
                                <input 
                                    type="text" 
                                    id="surname" 
                                    name="surname"
                                    class="form-control @error('surname') is-invalid @enderror"
                                    placeholder="Smith"
                                    value="{{ old('surname') }}"
                                    required
                                    autocomplete="family-name"
                                >
                                @error('surname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="phone" class="form-label">Telefónne číslo</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="+421 900 000 000"
                                    value="{{ old('phone') }}"
                                >
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark register-btn">
                                Zaregistrovať sa
                            </button>
                        </div>

                        <p class="text-center mt-4 mb-0 register-text">
                            Už máte účet? <a href="{{ route('login') }}">Prihláste sa</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection