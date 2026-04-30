<header class="sticky top-0 z-50 bg-background/95 backdrop-blur-sm border-b border-border">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 py-2 sm:py-4">
        <div class="grid grid-cols-[auto_1fr_auto] gap-x-3 gap-y-3 sm:grid-cols-[1fr_auto_1fr] sm:gap-y-0 items-center">
            <div class="col-start-1 row-start-1 flex items-center gap-2 justify-self-start min-w-0">
                <button
                    type="button"
                    class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-card text-foreground"
                    aria-label="Open menu"
                    data-mobile-menu-open
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M3 12h18M3 18h18"/>
                    </svg>
                </button>
                <a href="{{ route('frontend.home') }}" class="shrink-0">
                    <img src="/storage/logos/Logo2.png" alt="Tanzania national emblem" class="h-8 w-auto max-h-8 object-contain object-center sm:h-14 sm:max-h-[60px] md:h-[4.5rem] md:max-h-[72px]">
                </a>
            </div>

            <a href="{{ route('frontend.home') }}" class="hidden sm:block col-start-2 row-start-1 sm:col-span-1 sm:row-start-1 sm:col-start-2 justify-self-center text-center min-w-0 max-w-[min(100%,360px)] sm:max-w-xl md:max-w-2xl lg:max-w-4xl px-2">
                <p class="font-sans text-[0.5rem] font-extrabold uppercase leading-[1.02] tracking-wide text-foreground sm:text-[0.984375rem] md:text-[1.18125rem] lg:text-[1.575rem] xl:text-[1.96875rem]">Utafiti Elimu Tanzania</p>
                <p class="mt-0.5 sm:mt-2 font-sans text-[0.5rem] font-semibold uppercase leading-[1.02] tracking-wide text-foreground/85 sm:text-[0.984375rem] md:text-[1.18125rem] lg:text-[1.575rem] xl:text-[1.96875rem]">University of Dar es Salaam</p>
            </a>

            <div class="col-start-3 row-start-1 justify-self-end sm:col-start-3 sm:justify-self-end flex items-center py-0.5">
                <img src="/storage/logos/Logo1.png" alt="University of Dar es Salaam" class="h-8 w-auto max-h-8 object-contain object-center sm:h-14 sm:max-h-[60px] md:h-[4.5rem] md:max-h-[72px]">
            </div>
        </div>

        <nav class="hidden md:flex flex-wrap justify-center items-center gap-x-8 gap-y-2 border-t border-border/60 mt-3 pt-3">
            <a href="{{ route('frontend.home') }}" class="text-sm font-medium text-primary transition-colors hover:text-primary/80">Home</a>
            <a href="{{ route('frontend.events') }}" class="text-sm text-foreground/70 transition-colors hover:text-foreground">Events</a>
            <a href="{{ route('frontend.speakers') }}" class="text-sm text-foreground/70 transition-colors hover:text-foreground">Speakers</a>
            <a href="{{ route('frontend.resources') }}" class="text-sm text-foreground/70 transition-colors hover:text-foreground">Resources</a>
            <a href="{{ route('frontend.gallery') }}" class="text-sm text-foreground/70 transition-colors hover:text-foreground">Gallery</a>
            <a href="{{ route('frontend.topics') }}" class="text-sm text-foreground/70 transition-colors hover:text-foreground">Topics</a>
        </nav>
    </div>

    <div class="fixed inset-0 z-[999] hidden md:hidden" data-mobile-menu>
        <div class="absolute inset-0 bg-black/60 z-[1000]" data-mobile-menu-close></div>
        <aside class="absolute left-0 top-0 h-dvh w-72 max-w-[85vw] bg-card border-r border-border p-5 shadow-2xl z-[1001] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <span class="font-semibold text-foreground">Menu</span>
                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-background text-foreground"
                    aria-label="Close menu"
                    data-mobile-menu-close
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col gap-2 pb-8" data-mobile-menu-links>
                <a href="{{ route('frontend.home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-foreground hover:bg-muted">Home</a>
                <a href="{{ route('frontend.events') }}" class="rounded-lg px-3 py-2 text-sm text-foreground/80 hover:bg-muted">Events</a>
                <a href="{{ route('frontend.speakers') }}" class="rounded-lg px-3 py-2 text-sm text-foreground/80 hover:bg-muted">Speakers</a>
                <a href="{{ route('frontend.resources') }}" class="rounded-lg px-3 py-2 text-sm text-foreground/80 hover:bg-muted">Resources</a>
                <a href="{{ route('frontend.gallery') }}" class="rounded-lg px-3 py-2 text-sm text-foreground/80 hover:bg-muted">Gallery</a>
                <a href="{{ route('frontend.topics') }}" class="rounded-lg px-3 py-2 text-sm text-foreground/80 hover:bg-muted">Topics</a>
            </nav>
        </aside>
    </div>
</header>
