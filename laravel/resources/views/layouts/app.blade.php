<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ReadIt')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.topbar')
    @include('partials.navbar')

    <main class="py-5">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>