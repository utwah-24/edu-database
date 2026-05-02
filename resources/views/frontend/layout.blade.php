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

    <button
        type="button"
        id="scroll-to-top-btn"
        data-visible="false"
        class="fixed bottom-6 right-6 z-[60] inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#2C63AA] text-white shadow-lg shadow-black/20 ring-2 ring-white/20 transition-all duration-300 hover:bg-[#24528f] hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#2C63AA] opacity-0 translate-y-4 pointer-events-none data-[visible=true]:opacity-100 data-[visible=true]:translate-y-0 data-[visible=true]:pointer-events-auto sm:bottom-8 sm:right-8"
        aria-label="Scroll to top of page"
        aria-hidden="true"
    >
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
    </button>

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
        (() => {
            const btn = document.getElementById('scroll-to-top-btn');
            if (!btn) {
                return;
            }
            const THRESHOLD = 320;

            const sync = () => {
                const show = window.scrollY > THRESHOLD;
                btn.dataset.visible = show ? 'true' : 'false';
                btn.setAttribute('aria-hidden', show ? 'false' : 'true');
            };

            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            window.addEventListener('scroll', sync, { passive: true });
            sync();
        })();
    </script>
</body>
</html>
