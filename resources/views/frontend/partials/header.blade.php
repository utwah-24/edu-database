<div class="sticky top-0 z-[100] md:static md:z-auto">
    <header class="bg-background/95 border-b border-border">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 py-2 sm:py-4">
            <div class="grid grid-cols-[auto_1fr_auto] gap-x-3 gap-y-3 sm:grid-cols-[1fr_auto_1fr] sm:gap-y-0 items-center">
                <div class="col-start-1 row-start-1 flex items-center gap-2 justify-self-start min-w-0">
                    <a href="{{ route('frontend.home') }}" class="shrink-0">
                        <img src="{{ asset('/storage/logos/Logo2.png') }}" alt="Tanzania national emblem" class="h-8 w-auto max-h-8 object-contain object-center sm:h-14 sm:max-h-[60px] md:h-[4.5rem] md:max-h-[72px]">
                    </a>
                </div>

                <a href="{{ route('frontend.home') }}" class="hidden sm:block font-['Montserrat'] col-start-2 row-start-1 sm:col-span-1 sm:row-start-1 sm:col-start-2 justify-self-center text-center min-w-0 max-w-[min(100%,360px)] sm:max-w-xl md:max-w-2xl lg:max-w-4xl px-2">
                    <p class="text-[0.5rem] font-extrabold uppercase leading-[1.02] tracking-wide text-foreground sm:text-[0.984375rem] md:text-[1.18125rem] lg:text-[1.575rem] xl:text-[1.96875rem]">Utafiti Elimu Tanzania</p>
                    <p class="mt-0.5 sm:mt-2 text-[0.5rem] font-semibold uppercase leading-[1.02] tracking-wide text-[#2C63AA] sm:text-[0.984375rem] md:text-[1.18125rem] lg:text-[1.575rem] xl:text-[1.96875rem]">University of Dar es Salaam</p>
                </a>

                <div class="col-start-3 row-start-1 justify-self-end sm:col-start-3 sm:justify-self-end flex items-center justify-end gap-2 py-0.5">
                    <button
                        type="button"
                        class="inline-flex md:hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-[#2C63AA]/35 bg-[#2C63AA]/10 text-[#2C63AA] shadow-sm transition hover:bg-[#2C63AA]/15"
                        aria-label="Open menu"
                        aria-expanded="false"
                        data-mobile-menu-open
                    >
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M3 6h18M3 12h18M3 18h18"/>
                        </svg>
                    </button>
                    <img src="{{ asset('/storage/logos/Logo1.png') }}" alt="University of Dar es Salaam" class="hidden md:block h-8 w-auto max-h-8 object-contain object-center sm:h-14 sm:max-h-[60px] md:h-[4.5rem] md:max-h-[72px]">
                </div>
            </div>
        </div>
    </header>

    <nav class="header-desktop-nav w-full bg-[#2C63AA] border-t border-white/15 md:sticky md:top-0 md:z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 py-1.5 md:py-2">
            <div class="hidden md:flex flex-wrap justify-center items-center gap-x-8 gap-y-2 py-0.5">
                <a href="{{ route('frontend.home') }}" class="text-sm font-medium text-white transition-colors hover:text-white/85">Home</a>
                <a href="{{ route('frontend.events') }}" class="text-sm text-white/85 transition-colors hover:text-white">Events</a>
                <a href="{{ route('frontend.speakers') }}" class="text-sm text-white/85 transition-colors hover:text-white">Speakers</a>
                <a href="{{ route('frontend.resources') }}" class="text-sm text-white/85 transition-colors hover:text-white">Resources</a>
                <a href="{{ route('frontend.gallery') }}" class="text-sm text-white/85 transition-colors hover:text-white">Gallery</a>
                <a href="{{ route('frontend.topics') }}" class="text-sm text-white/85 transition-colors hover:text-white">Topics</a>
            </div>
        </div>
    </nav>
</div>
