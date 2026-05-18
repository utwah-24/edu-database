@php
    $social = config('footer.social', []);
    $socialKeysOrder = ['facebook', 'x', 'linkedin', 'instagram', 'youtube'];
    $socialAriaLabels = [
        'facebook' => 'Facebook',
        'x' => 'X (Twitter)',
        'linkedin' => 'LinkedIn',
        'instagram' => 'Instagram',
        'youtube' => 'YouTube',
    ];
    $contactMail = config('footer.contact_email');
    $navLinks = [
        ['label' => 'Home', 'route' => 'frontend.home'],
        ['label' => 'Events', 'route' => 'frontend.events'],
        ['label' => 'Speakers', 'route' => 'frontend.speakers'],
        ['label' => 'Topics', 'route' => 'frontend.topics'],
        ['label' => 'Resources', 'route' => 'frontend.resources'],
        ['label' => 'Gallery', 'route' => 'frontend.gallery'],
    ];
@endphp

<footer id="footer-site" class="mt-auto w-full shrink-0 border-t border-black/10 bg-[#2C63AA] pb-[env(safe-area-inset-bottom,0px)] text-white">
    <div class="mx-auto max-w-7xl px-6 py-8 sm:px-10 sm:py-9">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-10">
            {{-- Brand + home --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-3 rounded-lg transition-opacity hover:opacity-90">
                    <img src="{{ asset('logos/Logo2.png') }}" alt="Tanzania national emblem" class="h-11 w-auto object-contain object-center">
                    <span class="text-left leading-tight">
                        <span class="font-heading block text-sm font-bold uppercase tracking-wide">Utafiti Elimu</span>
                        <span class="block text-xs font-semibold text-white/80">Tanzania</span>
                    </span>
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/75">
                    Conference schedules, speakers, themes, and resources from the University of Dar es Salaam.
                </p>
                <nav class="mt-4 flex gap-2" aria-label="Social media">
                    @foreach($socialKeysOrder as $key)
                        @php $socUrl = is_string($social[$key] ?? '') ? trim($social[$key]) : ''; @endphp
                        @if($socUrl !== '')
                            <a
                                href="{{ $socUrl }}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/25"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="{{ $socialAriaLabels[$key] ?? $key }}"
                            >
                                @include('frontend.partials.footer-social-icon', ['network' => $key])
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>

            {{-- Navigation --}}
            <div>
                <h2 class="text-sm font-semibold text-white">Navigation</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    @foreach($navLinks as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" class="text-white/75 transition hover:text-white">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h2 class="text-sm font-semibold text-white">Contact</h2>
                <ul class="mt-3 space-y-2 text-sm text-white/75">
                    <li>University of Dar es Salaam</li>
                    <li>Dar es Salaam, Tanzania</li>
                    @if(filled($contactMail))
                        <li>
                            <a href="mailto:{{ $contactMail }}" class="text-white/90 underline-offset-2 hover:text-white hover:underline">
                                {{ $contactMail }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        @if(!empty($footerSponsors ?? []))
            <div class="mt-8 border-t border-white/15 pt-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-white/60">Partners &amp; sponsors</p>
                <ul class="mt-4 flex flex-wrap gap-3">
                    @foreach($footerSponsors as $sponsorRow)
                        @if(! is_array($sponsorRow))
                            @continue
                        @endif
                        @php
                            $sName = trim((string) ($sponsorRow['name'] ?? 'Sponsor'));
                            $sWebsite = trim((string) ($sponsorRow['website'] ?? ''));
                            $sLogo = $sponsorRow['logo'] ?? null;
                        @endphp
                        <li>
                            @if($sWebsite !== '')
                                <a href="{{ $sWebsite }}" class="flex h-8 items-center rounded-md bg-white px-3 py-1" target="_blank" rel="noopener noreferrer" title="{{ $sName }}">
                            @else
                                <span class="flex h-8 items-center rounded-md bg-white px-3 py-1" title="{{ $sName }}">
                            @endif
                            @if(filled($sLogo))
                                <img src="{{ $sLogo }}" alt="{{ $sName }}" class="h-5 w-auto max-w-[6rem] object-contain">
                            @else
                                <span class="text-[10px] font-semibold uppercase text-[#2C63AA]">{{ $sName }}</span>
                            @endif
                            @if($sWebsite !== '')
                                </a>
                            @else
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-3 border-t border-white/15 pt-5 text-xs text-white/60 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ config('footer.copyright_label') }}. All rights reserved.</p>
            <a href="{{ route('frontend.home') }}" class="text-white/75 transition hover:text-white">Home</a>
        </div>
    </div>
</footer>
