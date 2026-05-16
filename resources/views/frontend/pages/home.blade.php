@extends('frontend.layout')

@section('title', 'Event Archive')

@section('content')
    <section class="home-hero relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('/storage/Bg.jpeg') }}" alt="" class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-secondary/90"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 md:py-28">
            <span class="text-primary font-medium text-sm tracking-wide uppercase">Conference History</span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight tracking-tight mt-4">
                Utafiti Elimu <span class="text-primary">Tanzania</span>
            </h1>
            <p class="text-explanation text-white/70 text-lg leading-relaxed max-w-xl mt-6">
                Schedules, speakers, topic tracks, resources, and partners organized by conference year.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="#events" class="px-5 py-3 bg-primary text-primary-foreground rounded-xl font-medium text-sm hover:bg-primary/90 transition-colors">Explore Events</a>
                <a href="{{ route('frontend.speakers') }}" class="px-5 py-3 bg-white text-foreground rounded-xl font-medium text-sm hover:bg-muted transition-colors">View Speakers</a>
            </div>
        </div>
    </section>

    <section id="events" class="bg-muted/30 py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <p class="text-primary text-sm font-medium">01</p>
                    <h2 class="font-serif text-3xl font-bold mt-2">Current Events</h2>
                    <p class="text-explanation text-muted-foreground text-sm mt-2">Published conferences with details and highlights.</p>
                </div>
                <a href="{{ route('frontend.events') }}" class="text-primary font-medium text-sm hover:underline">Explore All Events</a>
            </div>

            @if(!empty($carousel_events))
                @php $c0 = $carousel_events[0]; @endphp

                <div id="current-event" class="mb-10" data-featured-carousel>
                    <script type="application/json" id="featured-events-carousel-data">@json($carousel_events)</script>

                    <article class="featured-event-card group relative overflow-hidden rounded-2xl border border-border/90 bg-card shadow-md ring-1 ring-black/[0.04] transition-shadow duration-300 hover:shadow-lg">

                        {{-- Visual panel --}}
                        <div class="featured-event-card__visual relative bg-gradient-to-br from-[#1e4d7a] via-[#2C63AA] to-[#163a5c]">
                            <div class="pointer-events-none absolute inset-0 z-10 bg-gradient-to-tr from-black/25 via-transparent to-white/5"></div>
                            <img
                                src="{{ $c0['cover_image_url'] }}"
                                alt=""
                                class="absolute inset-0 z-[15] h-full w-full object-cover opacity-95 transition-opacity duration-500 group-hover:opacity-100"
                                data-carousel-cover
                            >
                            <div class="absolute inset-0 z-20 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                            <div class="absolute inset-x-0 bottom-0 z-30 p-5 sm:p-6">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span data-carousel-year class="inline-flex rounded-full bg-white/95 px-3 py-0.5 text-xs font-bold text-[#2C63AA] shadow-sm">{{ $c0['year'] }}</span>
                                    <span class="hidden sm:inline-flex rounded-full border border-white/25 bg-white/10 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white/90">Conference</span>
                                </div>
                                <p data-carousel-title class="mt-2 max-w-xl text-base font-bold leading-snug text-white sm:text-lg">{{ $c0['title'] }}</p>
                            </div>
                        </div>

                        {{-- Content panel: dense layout to match fixed image height (no scroll) --}}
                        <div class="featured-event-card__content featured-event-card__content--dense relative border-t border-border/60 bg-background lg:border-l-4 lg:border-t-0 lg:border-l-[#2C63AA]">
                            <p data-carousel-eyebrow class="text-[10px] font-bold uppercase tracking-[0.18em] text-primary">{{ $c0['card_eyebrow'] }}</p>
                            <h2 data-carousel-headline class="mt-0.5 line-clamp-2 font-serif text-base font-bold leading-tight text-foreground lg:text-lg">{{ $c0['card_headline'] }}</h2>

                            <div class="mt-2 grid gap-1.5 text-[11px] leading-snug text-muted-foreground sm:grid-cols-2">
                                <p class="flex min-h-0 items-start gap-1.5 rounded-lg border border-border/70 bg-muted/30 px-2 py-1.5">
                                    <svg class="mt-px h-3 w-3 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                                    <span data-carousel-location class="line-clamp-2">{{ $c0['location'] }}</span>
                                </p>
                                <p class="flex items-center gap-1.5 rounded-lg border border-border/70 bg-muted/30 px-2 py-1.5">
                                    <svg class="h-3 w-3 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                                    <span data-carousel-dates class="line-clamp-1">{{ $c0['date_range_label'] }}</span>
                                </p>
                            </div>

                            <p data-carousel-description class="text-explanation mt-2 line-clamp-2 text-[11px] leading-snug text-foreground/80 {{ $c0['card_description'] === '' ? 'hidden' : '' }}">{{ $c0['card_description'] }}</p>

                            <div class="featured-event-card__stats mt-2 flex shrink-0 gap-2">
                                <div class="flex min-w-0 flex-1 flex-col items-center justify-center rounded-lg border border-border/80 bg-muted/40 px-2 py-1.5 text-center">
                                    <p data-carousel-speakers class="text-lg font-bold tabular-nums leading-none text-foreground">{{ $c0['speaker_count'] }}</p>
                                    <p class="mt-0.5 text-[9px] font-semibold uppercase tracking-wide text-muted-foreground">Speakers</p>
                                </div>
                                <div class="flex min-w-0 flex-1 flex-col items-center justify-center rounded-lg border border-border/80 bg-muted/40 px-2 py-1.5 text-center">
                                    <p data-carousel-resources class="text-lg font-bold tabular-nums leading-none text-foreground">{{ $c0['resource_count'] }}</p>
                                    <p class="mt-0.5 text-[9px] font-semibold uppercase tracking-wide text-muted-foreground">Resources</p>
                                </div>
                            </div>

                            <div data-carousel-highlights-wrap class="featured-event-card__highlights mt-2 min-h-0 flex-1 overflow-hidden {{ empty($c0['highlights']) ? 'hidden' : '' }}">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-foreground/65">Programme highlights</p>
                                <div data-carousel-highlights class="mt-1 flex flex-wrap gap-1">
                                    @foreach($c0['highlights'] as $tag)
                                        <span class="inline-flex max-w-full truncate rounded-full border border-primary/30 bg-primary/10 px-2 py-0.5 text-[10px] font-medium leading-tight text-primary">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="featured-event-card__footer mt-auto flex shrink-0 flex-col gap-1.5 border-t border-border/60 pt-1.5 sm:flex-row sm:items-center sm:justify-between sm:pt-2">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5" data-carousel-nav>
                                        <button
                                            type="button"
                                            data-carousel-prev
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-border bg-background text-foreground shadow-sm transition hover:border-primary/40 hover:bg-primary/5 hover:text-primary disabled:pointer-events-none disabled:opacity-40"
                                            aria-label="Previous event"
                                            @if(count($carousel_events) < 2) disabled @endif
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                                        </button>
                                        <button
                                            type="button"
                                            data-carousel-next
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-border bg-background text-foreground shadow-sm transition hover:border-primary/40 hover:bg-primary/5 hover:text-primary disabled:pointer-events-none disabled:opacity-40"
                                            aria-label="Next event"
                                            @if(count($carousel_events) < 2) disabled @endif
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                        </button>
                                    </div>
                                    <div data-carousel-dots class="hidden items-center gap-1.5 sm:flex"></div>
                                </div>
                                <a
                                    href="{{ $c0['detail_url'] }}"
                                    data-carousel-detail
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 sm:w-auto"
                                >
                                    View details
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                <script>
                    (() => {
                        const root = document.querySelector('[data-featured-carousel]');
                        const raw = document.getElementById('featured-events-carousel-data');
                        if (!root || !raw) return;
                        let slides;
                        try { slides = JSON.parse(raw.textContent || '[]'); } catch (e) { return; }
                        if (!Array.isArray(slides) || slides.length === 0) return;

                        const $ = (sel) => root.querySelector(sel);
                        const cover = $('[data-carousel-cover]');
                        const year = $('[data-carousel-year]');
                        const title = $('[data-carousel-title]');
                        const eyebrow = $('[data-carousel-eyebrow]');
                        const headline = $('[data-carousel-headline]');
                        const loc = $('[data-carousel-location]');
                        const dates = $('[data-carousel-dates]');
                        const desc = $('[data-carousel-description]');
                        const spk = $('[data-carousel-speakers]');
                        const res = $('[data-carousel-resources]');
                        const hiWrap = $('[data-carousel-highlights-wrap]');
                        const hiBox = $('[data-carousel-highlights]');
                        const prev = $('[data-carousel-prev]');
                        const next = $('[data-carousel-next]');
                        const dots = $('[data-carousel-dots]');
                        const detail = $('[data-carousel-detail]');

                        let i = 0;
                        const setDots = () => {
                            if (!dots) return;
                            dots.innerHTML = '';
                            if (slides.length < 2) { dots.classList.add('hidden'); return; }
                            dots.classList.remove('hidden');
                            slides.forEach((_, idx) => {
                                const b = document.createElement('button');
                                b.type = 'button';
                                b.className = 'h-2 w-2 rounded-full transition ' + (idx === i ? 'bg-primary scale-110' : 'bg-border hover:bg-primary/50');
                                b.setAttribute('aria-label', 'Go to event ' + (idx + 1));
                                b.addEventListener('click', () => { i = idx; render(); });
                                dots.appendChild(b);
                            });
                        };

                        const render = () => {
                            const d = slides[i] || {};
                            if (cover) { cover.src = d.cover_image_url || ''; cover.alt = d.title || ''; }
                            if (year) year.textContent = d.year || '';
                            if (title) title.textContent = d.title || '';
                            if (eyebrow) eyebrow.textContent = d.card_eyebrow || '';
                            if (headline) headline.textContent = d.card_headline || '';
                            if (loc) loc.textContent = d.location || '';
                            if (dates) dates.textContent = d.date_range_label || '';
                            if (desc) {
                                const t = (d.card_description || '').trim();
                                desc.textContent = t;
                                desc.classList.toggle('hidden', t === '');
                            }
                            if (spk) spk.textContent = String(d.speaker_count ?? 0);
                            if (res) res.textContent = String(d.resource_count ?? 0);
                            const hl = Array.isArray(d.highlights) ? d.highlights : [];
                            if (hiWrap && hiBox) {
                                hiWrap.classList.toggle('hidden', hl.length === 0);
                                hiBox.innerHTML = '';
                                hl.forEach((tag) => {
                                    const s = document.createElement('span');
                                    s.className = 'inline-flex max-w-full truncate rounded-full border border-primary/30 bg-primary/10 px-2 py-0.5 text-[10px] font-medium leading-tight text-primary';
                                    s.textContent = tag;
                                    hiBox.appendChild(s);
                                });
                            }
                            if (detail) {
                                detail.href = d.detail_url || '#';
                                detail.setAttribute('aria-label', 'View details for ' + (d.title || 'event'));
                            }
                            const single = slides.length < 2;
                            if (prev) prev.disabled = single;
                            if (next) next.disabled = single;
                            setDots();
                        };

                        if (prev) prev.addEventListener('click', () => { i = (i - 1 + slides.length) % slides.length; render(); });
                        if (next) next.addEventListener('click', () => { i = (i + 1) % slides.length; render(); });
                        render();
                    })();
                </script>
            @endif

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($events as $item)
                    <article class="group overflow-hidden rounded-3xl border border-border bg-card hover:shadow-lg hover:border-primary/40 transition-all">
                        <div class="relative h-52">
                            <img src="{{ $item['cover_image_url'] ?? ($item['cover_image'] ?? '/placeholder.jpg') }}" alt="{{ $item['title'] ?? 'Event' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="font-serif text-2xl font-bold text-foreground group-hover:text-primary transition-colors">{{ $item['title'] ?? 'Event' }}</h3>
                            <p class="text-muted-foreground text-sm mt-2">{{ $item['location'] ?? 'Location TBA' }}</p>
                            <a href="{{ route('frontend.events.show', $item['id']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors mt-4">
                                View Details
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="speakers" class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <p class="text-primary text-sm font-medium">02</p>
                    <h2 class="font-serif text-3xl font-bold mt-2">Featured Speakers</h2>
                </div>
                <a href="{{ route('frontend.speakers') }}" class="text-primary font-medium text-sm hover:underline">View All Speakers</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($speakers as $speaker)
                    <article class="bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:border-primary/30 transition-all duration-300">
                        <div class="p-5 space-y-4 text-sm">
                            <div class="flex justify-center">
                                <div class="relative h-28 w-28 shrink-0 overflow-hidden rounded-full bg-muted shadow-md ring-2 ring-border">
                                    <img src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}" alt="{{ $speaker['name'] ?? 'Speaker' }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                            <h3 class="font-serif text-xl font-bold text-foreground leading-snug">{{ $speaker['name'] ?? 'Speaker' }}</h3>
                            <p class="text-muted-foreground text-sm">{{ $speaker['title'] ?? '' }} {{ !empty($speaker['organization']) ? ' · '.$speaker['organization'] : '' }}</p>
                            <div class="flex flex-wrap gap-2">
                                @if(($speaker['is_key_speaker'] ?? $speaker['key_speaker'] ?? false))
                                    <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded">Key Speaker</span>
                                @endif
                                @if(($speaker['is_session_leader'] ?? $speaker['session_leader'] ?? false))
                                    <span class="px-2 py-0.5 bg-muted text-foreground text-xs font-medium rounded">Session Leader</span>
                                @endif
                            </div>
                            <a href="{{ route('frontend.speakers') }}" class="text-primary text-sm font-medium hover:underline">View Speakers</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="topics" class="py-14 sm:py-20 bg-white text-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <p class="text-primary text-sm font-medium">03</p>
                    <h2 class="font-serif text-3xl font-bold mt-2">Topic Tracks</h2>
                </div>
                <a href="{{ route('frontend.topics') }}" class="text-primary font-medium text-sm hover:underline">Show All Topics</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($topics as $topic)
                    <article class="group p-6 rounded-2xl border border-border transition-all hover:border-primary/30 hover:shadow-sm">
                        <h3 class="font-serif text-xl font-semibold text-foreground group-hover:text-primary transition-colors">
                            {{ $topic['title'] ?? 'Topic' }}
                        </h3>
                        <p class="text-explanation text-muted-foreground text-sm leading-relaxed mt-3 line-clamp-4">
                            {{ \Illuminate\Support\Str::limit(strip_tags($topic['content'] ?? ''), 150) }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="resources" class="py-14 sm:py-20 bg-muted/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <p class="text-primary text-sm font-medium">04</p>
                    <h2 class="font-serif text-3xl font-bold mt-2">Resources & Downloads</h2>
                </div>
                <a href="{{ route('frontend.resources') }}" class="text-primary font-medium text-sm hover:underline">View All Resources</a>
            </div>
            <div class="space-y-4">
                @foreach($resources as $resource)
                    <article class="group bg-card rounded-2xl border border-border p-6 hover:shadow-lg hover:border-primary/30 transition-all">
                        <h3 class="font-serif text-xl font-semibold text-foreground group-hover:text-primary transition-colors">
                            {{ $resource['title'] ?? 'Resource' }}
                        </h3>
                        @if(!empty($resource['description']))
                            <p class="text-explanation text-muted-foreground text-sm mt-2">{{ $resource['description'] }}</p>
                        @endif
                        @if(!empty($resource['url']) || !empty($resource['file_path']))
                            <a href="{{ $resource['url'] ?? $resource['file_path'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors mt-4">
                                Open Resource
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="sponsors" class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="mb-12">
                <p class="text-primary text-sm font-medium">05</p>
                <h2 class="font-serif text-3xl font-bold mt-2">Our Partners</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($sponsors as $sponsor)
                    <article class="group p-6 bg-card rounded-xl border border-border hover:shadow-lg hover:border-primary/30 transition-all text-center">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-xl bg-muted flex items-center justify-center overflow-hidden">
                            <img src="{{ $sponsor['logo'] ?? '/placeholder-logo.png' }}" alt="{{ $sponsor['name'] ?? 'Sponsor' }}" class="w-full h-full object-contain p-1">
                        </div>
                        <h3 class="font-semibold text-foreground">{{ $sponsor['name'] ?? 'Sponsor' }}</h3>
                        @if(!empty($sponsor['tier']))
                            <p class="text-xs text-muted-foreground mt-1">{{ $sponsor['tier'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
