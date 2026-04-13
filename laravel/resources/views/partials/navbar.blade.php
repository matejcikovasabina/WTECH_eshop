<nav>
    <div class="navigacka container-fluid d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="ReadIt logo" class="logo-img">
        </a>

        <button class="menu-toggle" type="button" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <div class="nav-content" id="navContent">
            <ul class="nav">    
                <li class="nav-item"><a class="nav-link" href="#">Novinky</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Knihy
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                        <li><a class="dropdown-item" href="#">Beletria</a></li>
                        <li><a class="dropdown-item" href="#">Náučné</a></li>
                        <li><a class="dropdown-item" href="#">Životopisy</a></li>
                        <li><a class="dropdown-item" href="#">Cestovateľské</a></li>
                        <li><a class="dropdown-item" href="#">Kuchárske</a></li>
                        <li><a class="dropdown-item" href="#">Učebnice a slovníky</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('books.index') }}">Všetky kategórie</a></li>
                    </ul>
                </li>               
                <li class="nav-item"><a class="nav-link" href="#">Akcie a Zľavy</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Poukážky</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Doplnky</a></li>
            </ul>

            <ul class="nav">
                <li class="nav-item search-item">
                    <form action="{{ route('books.index') }}" method="GET">
                        <div class="input-group search-group">
                            <input 
                                type="text" 
                                name="search" {{-- DOLEŽITÉ: meno, ktoré kontrolér hľadá --}}
                                class="form-control border-start-0 ps-0" 
                                placeholder="Vyhľadávanie" 
                                aria-label="Vyhľadávanie"
                                value="{{ request('search') }}" {{-- Aby text zostal v poli po vyhľadaní --}}
                            >
                            <button class="btn btn-search" type="submit">
                                <img src="{{ asset('images/search.png') }}" alt="Hľadať" width="18" height="18">
                            </button>
                        </div>
                    </form>
                </li>

                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle user-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @guest
                            Login
                        @endguest

                        @auth
                            <span class="user-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        @endauth
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        @guest
                            <li><a class="dropdown-item" href="{{ route('login') }}">Prihlásiť sa</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Registrácia</a></li>
                        @endguest

                        @auth
                            <li><a class="dropdown-item" href="#">Profil</a></li>
                            <li><a class="dropdown-item" href="#">Moje objednávky</a></li>
                            <li><a class="dropdown-item" href="#">Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log Out</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Košík</a></li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const navContent = document.getElementById('navContent');
        navContent.classList.toggle('active');
    }
</script>