<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Event Archive')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700|open-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    @include('frontend.partials.header')
    <main class="min-h-screen">
        @yield('content')
    </main>
    @include('frontend.partials.footer')
    <script>
        (() => {
            const menu = document.querySelector('[data-mobile-menu]');
            const openBtn = document.querySelector('[data-mobile-menu-open]');
            const closeBtns = document.querySelectorAll('[data-mobile-menu-close]');
            const menuLinks = document.querySelectorAll('[data-mobile-menu-links] a');
            if (!menu || !openBtn) return;

            const openMenu = () => {
                menu.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeMenu = () => {
                menu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn.addEventListener('click', openMenu);
            closeBtns.forEach((btn) => btn.addEventListener('click', closeMenu));
            menuLinks.forEach((link) => link.addEventListener('click', closeMenu));
        })();
    </script>
</body>
</html>
