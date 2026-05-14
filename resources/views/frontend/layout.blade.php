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

    <div
        class="fixed inset-0 z-[110000] hidden md:hidden"
        data-mobile-menu
        aria-hidden="true"
    >
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 ease-out data-[open]:opacity-100"
            data-mobile-menu-backdrop
            data-mobile-menu-close
        ></div>
        <aside
            class="absolute left-0 top-0 flex h-dvh w-[min(20rem,88vw)] max-w-[88vw] flex-col rounded-r-2xl border border-white/10 bg-gradient-to-b from-card via-card to-muted/30 shadow-[4px_0_40px_-8px_rgba(0,0,0,0.35)] transition-transform duration-300 ease-out -translate-x-full data-[open]:translate-x-0 overflow-hidden pt-[env(safe-area-inset-top,0px)]"
            data-mobile-menu-panel
            role="dialog"
            aria-modal="true"
            aria-label="Site menu"
        >
            <div class="border-b border-[#2C63AA]/15 bg-[#2C63AA]/10 px-4 py-3">
                <div class="flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-border/80 bg-background text-foreground shadow-sm transition hover:bg-muted"
                        aria-label="Close menu"
                        data-mobile-menu-close
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-4 pb-[max(1.5rem,env(safe-area-inset-bottom))]" data-mobile-menu-links>
                <a href="{{ route('frontend.home') }}" class="rounded-xl px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-[#2C63AA]/10">Home</a>
                <a href="{{ route('frontend.events') }}" class="rounded-xl px-3 py-2.5 text-sm text-foreground/85 transition hover:bg-[#2C63AA]/10">Events</a>
                <a href="{{ route('frontend.speakers') }}" class="rounded-xl px-3 py-2.5 text-sm text-foreground/85 transition hover:bg-[#2C63AA]/10">Speakers</a>
                <a href="{{ route('frontend.resources') }}" class="rounded-xl px-3 py-2.5 text-sm text-foreground/85 transition hover:bg-[#2C63AA]/10">Resources</a>
                <a href="{{ route('frontend.gallery') }}" class="rounded-xl px-3 py-2.5 text-sm text-foreground/85 transition hover:bg-[#2C63AA]/10">Gallery</a>
                <a href="{{ route('frontend.topics') }}" class="rounded-xl px-3 py-2.5 text-sm text-foreground/85 transition hover:bg-[#2C63AA]/10">Topics</a>
            </nav>
        </aside>
    </div>

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
            const backdrop = document.querySelector('[data-mobile-menu-backdrop]');
            const panel = document.querySelector('[data-mobile-menu-panel]');
            const closeBtns = document.querySelectorAll('[data-mobile-menu-close]');
            const menuLinks = document.querySelectorAll('[data-mobile-menu-links] a');
            if (!menu || !openBtn) return;

            const DRAWER_MS = 300;

            const setOpenState = (open) => {
                menu.setAttribute('aria-hidden', open ? 'false' : 'true');
                openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (backdrop) backdrop.toggleAttribute('data-open', open);
                if (panel) panel.toggleAttribute('data-open', open);
            };

            const openMenu = () => {
                menu.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => setOpenState(true));
                });
            };

            const closeMenu = () => {
                setOpenState(false);
                window.setTimeout(() => {
                    menu.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, DRAWER_MS);
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
