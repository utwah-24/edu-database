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
@endphp

<footer id="footer-site" class="mt-20 bg-[#2C63AA] text-white">
    <div class="max-w-7xl mx-auto px-5 pt-14 pb-10 sm:px-8 lg:px-10 lg:pt-16 lg:pb-11">
        <div class="grid gap-11 sm:gap-12 md:grid-cols-2 md:gap-x-14">
            <div class="md:max-w-md">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-white/70">Quick links</h2>
                <nav aria-label="Footer quick links">
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('frontend.home') }}" class="text-white/90 transition-colors hover:text-white">Home</a></li>
                        <li><a href="{{ route('frontend.events') }}" class="text-white/90 transition-colors hover:text-white">Events</a></li>
                        <li><a href="{{ route('frontend.speakers') }}" class="text-white/90 transition-colors hover:text-white">Speakers</a></li>
                        <li><a href="{{ route('frontend.topics') }}" class="text-white/90 transition-colors hover:text-white">Topics</a></li>
                        <li><a href="{{ route('frontend.resources') }}" class="text-white/90 transition-colors hover:text-white">Resources</a></li>
                        <li><a href="{{ route('frontend.gallery') }}" class="text-white/90 transition-colors hover:text-white">Gallery</a></li>
                    </ul>
                </nav>
            </div>

            <div class="md:max-w-md">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-white/70">Contact</h2>
                @if(filled($contactMail))
                    <a href="mailto:{{ $contactMail }}" class="mt-4 inline-flex text-sm text-white/90 transition-colors hover:text-white underline underline-offset-4">{{ $contactMail }}</a>
                @else
                    <p class="mt-4 text-sm text-white/65">General enquiries: add an email address in site configuration.</p>
                @endif
            </div>
        </div>

        @if(!empty($footerSponsors ?? []))
            <div class="mt-14 pt-10 border-t border-white/15">
                <h2 class="text-center text-xs font-semibold uppercase tracking-wider text-white/70">Partners &amp; sponsors</h2>
                <p class="mt-2 text-center text-xs text-white/55 max-w-xl mx-auto">
                    Supporting organisations for the highlighted conference edition.
                </p>
                <ul class="mt-8 flex flex-wrap justify-center items-center gap-6 sm:gap-10">
                    @foreach($footerSponsors as $sponsorRow)
                        @if(! is_array($sponsorRow))
                            @continue
                        @endif
                        @php
                            $sName = trim((string) ($sponsorRow['name'] ?? 'Sponsor'));
                            $sWebsite = trim((string) ($sponsorRow['website'] ?? ''));
                            $sLogo = $sponsorRow['logo'] ?? null;
                        @endphp
                        <li class="shrink-0">
                            @if($sWebsite !== '')
                                <a href="{{ $sWebsite }}" class="group block rounded-xl bg-white px-5 py-3 shadow-sm shadow-black/10 transition-opacity hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#2C63AA]" target="_blank" rel="noopener noreferrer" title="{{ $sName }}">
                            @else
                                <span class="block rounded-xl bg-white px-5 py-3 shadow-sm shadow-black/10" title="{{ $sName }}">
                            @endif
                            @if(filled($sLogo))
                                <img src="{{ $sLogo }}" alt="{{ $sName }}" class="h-10 w-auto max-w-[160px] sm:h-11 object-contain object-center mx-auto pointer-events-none">
                            @else
                                <span class="block max-w-[10rem] text-center text-xs font-semibold uppercase tracking-wide text-[#2C63AA]">{{ $sName }}</span>
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

        <div class="mt-12 lg:mt-14 pt-8 border-t border-white/15 flex flex-col sm:flex-row items-center justify-between gap-7 text-sm text-white/80">
            <p class="text-center sm:text-left">
                &copy; {{ date('Y') }} {{ config('footer.copyright_label') }}. All rights reserved.
            </p>
            <nav class="flex flex-wrap items-center justify-center gap-2 sm:justify-end sm:gap-3" aria-label="Social media">
                @foreach($socialKeysOrder as $key)
                    @php
                        $socUrlRaw = $social[$key] ?? '';
                        $socUrl = is_string($socUrlRaw) ? trim($socUrlRaw) : '';
                    @endphp
                    @if($socUrl !== '')
                        <a
                            href="{{ $socUrl }}"
                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15 text-white transition-colors hover:bg-white/25 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#2C63AA]"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="{{ $socialAriaLabels[$key] ?? $key }}"
                        >
                            @include('frontend.partials.footer-social-icon', ['network' => $key])
                        </a>
                    @else
                        <span
                            class="inline-flex h-10 w-10 shrink-0 cursor-default items-center justify-center rounded-full bg-white/10 text-white/55"
                            role="img"
                            aria-label="{{ ($socialAriaLabels[$key] ?? $key).' (link not configured)' }}"
                            title="{{ $socialAriaLabels[$key] ?? $key }}"
                        >
                            @include('frontend.partials.footer-social-icon', ['network' => $key])
                        </span>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
</footer>
