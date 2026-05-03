@extends('frontend.layout')

@section('title', 'Event Archive')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('/storage/Bg.jpeg') }}" alt="Conference background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-secondary/90"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 md:py-28">
            <span class="text-primary font-medium text-sm tracking-wide uppercase">Conference History</span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight tracking-tight mt-4">
                Utafiti Elimu <span class="text-primary">Tanzania</span>
            </h1>
            <p class="text-white/70 text-lg leading-relaxed max-w-xl mt-6">
                Schedules, speakers, topic tracks, resources, and partners organized by conference year.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="#events" class="px-5 py-3 bg-primary text-primary-foreground rounded-xl font-medium text-sm hover:bg-primary/90 transition-colors">Explore Events</a>
                <a href="{{ route('frontend.speakers') }}" class="px-5 py-3 bg-white text-foreground rounded-xl font-medium text-sm hover:bg-muted transition-colors">View Speakers</a>
            </div>
        </div>
    </section>

    <section id="events" class="py-14 sm:py-20 bg-muted/30 mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                <div>
                    <p class="text-primary text-sm font-medium">01</p>
                    <h2 class="font-serif text-3xl font-bold mt-2">Past Events</h2>
                    <p class="text-muted-foreground text-sm mt-2">Published conferences with details and highlights.</p>
                </div>
                <a href="{{ route('frontend.events') }}" class="text-primary font-medium text-sm hover:underline">Explore All Events</a>
            </div>

            @if(!empty($event))
                @php
                    $programmesList = collect($event['programmes'] ?? [])->sortBy(fn ($p) => (int) ($p['order'] ?? 0))->values();
                    $primaryProgramme = $programmesList->first();
                    $themeRow = collect($event['themes'] ?? [])->first();

                    $cardHeadline = $primaryProgramme['title'] ?? ($themeRow['theme'] ?? ($event['title'] ?? 'Event'));
                    $cardEyebrow = $primaryProgramme ? 'Primary theme' : (($themeRow['theme'] ?? '') !== '' ? 'Conference theme' : 'Featured event');

                    $firstSummary = collect($event['summaries'] ?? [])->first();
                    $summarySource = (string) ($primaryProgramme['description'] ?? ($firstSummary['summary'] ?? ''));
                    $cardDescription = \Illuminate\Support\Str::limit(trim(strip_tags($summarySource)), 200);

                    $start = ! empty($event['start_date']) ? \Carbon\Carbon::parse($event['start_date']) : null;
                    $end = ! empty($event['end_date']) ? \Carbon\Carbon::parse($event['end_date']) : null;
                    if ($start && $end && $start->month === $end->month && $start->year === $end->year) {
                        $dateRangeLabel = $start->format('F j').'–'.$end->format('j, Y');
                    } elseif ($start && $end) {
                        $dateRangeLabel = $start->format('F j, Y').' – '.$end->format('F j, Y');
                    } elseif ($start) {
                        $dateRangeLabel = $start->format('F j, Y');
                    } else {
                        $dateRangeLabel = trim(($event['start_date_formatted'] ?? '').(! empty($event['end_date_formatted']) ? ' – '.$event['end_date_formatted'] : '')) ?: 'Date TBA';
                    }

                    $coverSrc = $event['cover_image_url'] ?? ($event['cover_image'] ?? '/placeholder.jpg');
                    $speakerCount = count($event['speakers'] ?? []);
                    $resourceCount = count($event['resources'] ?? []);
                @endphp

                <div id="current-event" class="mb-10">
                    <article class="featured-event-card border border-border bg-card shadow-sm">

                        {{-- ── Visual panel ── --}}
                        <div class="featured-event-card__visual bg-gradient-to-br from-sky-700 via-sky-600 to-teal-600">
                            {{-- orange circle --}}
                            <div class="pointer-events-none absolute right-0 top-1/2 z-0 h-56 w-56 -translate-y-1/2 translate-x-1/4 rounded-full bg-orange-500"></div>
                            {{-- subtle tint --}}
                            <div class="absolute inset-0 z-10 bg-blue-900/20"></div>
                            {{-- cover photo --}}
                            <img src="{{ $coverSrc }}" alt="{{ $event['title'] ?? '' }}"
                                 class="absolute inset-0 z-20 h-full w-full object-cover">
                            {{-- bottom scrim --}}
                            <div class="absolute inset-0 z-30 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                            {{-- year + title --}}
                            <div class="absolute inset-x-0 bottom-0 z-40 p-5">
                                <span class="inline-flex rounded-full bg-primary px-3 py-0.5 text-xs font-semibold text-primary-foreground">{{ $event['year'] ?? '' }}</span>
                                <p class="mt-2 text-base font-bold leading-tight text-white lg:text-lg">{{ $event['title'] ?? '' }}</p>
                            </div>
                        </div>

                        {{-- ── Content panel ── --}}
                        <div class="featured-event-card__content bg-background">

                            <p class="text-xs font-semibold uppercase tracking-widest text-primary">{{ $cardEyebrow }}</p>
                            <h2 class="mt-1 font-serif text-lg font-bold leading-snug text-foreground">{{ $cardHeadline }}</h2>

                            <div class="mt-3 space-y-1 text-sm text-muted-foreground">
                                <p class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                                    {{ $event['location'] ?? 'Location TBA' }}
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                                    {{ $dateRangeLabel }}
                                </p>
                            </div>

                            @if($cardDescription !== '')
                                <p class="mt-3 text-sm leading-relaxed text-foreground/80 line-clamp-3">{{ $cardDescription }}</p>
                            @endif

                            {{-- Stats --}}
                            <div class="mt-4 flex gap-3">
                                <div class="flex-1 rounded-xl border border-border bg-muted px-4 py-3 text-center">
                                    <p class="font-serif text-2xl font-bold text-foreground">{{ $speakerCount }}</p>
                                    <p class="text-xs font-medium text-muted-foreground">Speakers</p>
                                </div>
                                <div class="flex-1 rounded-xl border border-border bg-muted px-4 py-3 text-center">
                                    <p class="font-serif text-2xl font-bold text-foreground">{{ $resourceCount }}</p>
                                    <p class="text-xs font-medium text-muted-foreground">Resources</p>
                                </div>
                            </div>

                            {{-- Programme highlights --}}
                            @if(!empty($event_topic_highlights))
                                <div class="mt-4">
                                    <p class="text-xs font-semibold text-foreground">Programme highlights</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($event_topic_highlights as $htopic)
                                            @if(!empty($htopic['title']))
                                                <span class="inline-flex rounded-full border border-primary/40 bg-primary/10 px-3 py-1 text-xs font-medium text-primary">{{ $htopic['title'] }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Footer --}}
                            <div class="mt-auto flex items-center justify-between border-t border-border pt-4">
                                <div class="flex items-center gap-2" aria-hidden="true">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-border bg-background text-muted-foreground">‹</span>
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-border bg-background text-muted-foreground">›</span>
                                    <span class="ml-1 inline-block h-0.5 w-8 rounded-full bg-primary"></span>
                                </div>
                                <a href="{{ route('frontend.events.show', $event['id']) }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                                    View Details
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        </div>

                    </article>
                </div>
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
                        <p class="text-muted-foreground text-sm leading-relaxed mt-3 line-clamp-4">
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
                            <p class="text-muted-foreground text-sm mt-2">{{ $resource['description'] }}</p>
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
