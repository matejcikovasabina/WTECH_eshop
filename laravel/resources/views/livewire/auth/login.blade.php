@extends('layouts.auth')

@section('title', 'Prihlásenie')

@section('content')
<section class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
            <div class="login-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="text-center mb-4">Prihlásenie</h1>

                    @if (session('status'))
                        <div class="alert alert-success text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@gmail.com"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Heslo</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Zadajte heslo"
                                required
                                autocomplete="current-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="remember" 
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Zapamätať si ma
                            </label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-dark login-btn">
                                Prihlásiť sa
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <p class="text-center mb-0 login-text">
                                Nemáte účet? <a href="{{ route('register') }}">Zaregistrujte sa</a>
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection