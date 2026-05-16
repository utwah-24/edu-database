@extends('frontend.layout')

@section('title', $event['title'] ?? 'Event')

@section('content')
    @php
        $programmesSorted = collect($event['programmes'] ?? [])->sortBy(fn ($p) => (int) ($p['order'] ?? 0))->values();
        $heroProgramme = $programmesSorted->first();
        $heroFromProgramme = is_array($heroProgramme) && filled($heroProgramme['description'] ?? null);
        $heroIntroHtml = $heroFromProgramme
            ? (string) $heroProgramme['description']
            : (string) (collect($event['summaries'] ?? [])->first()['summary'] ?? '');
        $heroCoverSrc = $event['cover_image_url'] ?? ($event['cover_image'] ?? '');
    @endphp

    <section class="event-detail-hero event-detail-hero--fit overflow-hidden text-white min-h-[13rem]">
        <div class="event-detail-hero__media">
            @if($heroCoverSrc !== '')
                <img src="{{ $heroCoverSrc }}" alt="" decoding="async">
            @else
                <div class="h-full w-full bg-gradient-to-br from-[#163a5c] via-[#2C63AA] to-[#1e4d7a]"></div>
            @endif
        </div>
        <div class="event-detail-hero__overlay" aria-hidden="true"></div>
        <div class="event-detail-hero__veil" aria-hidden="true"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 lg:py-10">
            <a href="{{ route('frontend.events') }}" class="inline-flex w-fit items-center gap-2 rounded-full border border-white/25 bg-white/10 px-3.5 py-1.5 text-xs font-medium text-white/90 backdrop-blur-sm transition hover:border-white/40 hover:bg-white/20 sm:px-4 sm:py-2 sm:text-sm">
                <svg class="h-4 w-4 shrink-0 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                Back to events
            </a>

            <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-primary/95 sm:mt-5 sm:text-xs">Conference</p>
            <h1 class="mt-1.5 max-w-4xl font-serif text-2xl font-bold leading-tight text-white drop-shadow-sm sm:text-3xl lg:text-4xl">{{ $event['title'] ?? 'Event' }}</h1>

            <div class="mt-4 flex max-w-2xl flex-col gap-2 text-xs text-white/90 sm:mt-5 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-4 sm:gap-y-2 sm:text-sm">
                <p class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-black/20 px-3 py-2 backdrop-blur-sm">
                    <svg class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                    <span>{{ $event['location'] ?? 'Location TBA' }}</span>
                </p>
                <p class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-black/20 px-3 py-2 backdrop-blur-sm">
                    <svg class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                    <span>
                        {{ $event['start_date_formatted'] ?? 'Date TBA' }}
                        @if(!empty($event['end_date_formatted']))
                            <span class="text-white/70"> — </span>{{ $event['end_date_formatted'] }}
                        @endif
                    </span>
                </p>
                @if(!empty($event['year']))
                    <p class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-black/20 px-3 py-2 backdrop-blur-sm">
                        <span class="text-xs font-semibold uppercase tracking-wider text-white/70">Year</span>
                        <span class="font-semibold tabular-nums">{{ $event['year'] }}</span>
                    </p>
                @endif
            </div>

            @if($heroIntroHtml !== '')
                <details class="event-detail-hero__overview mt-5 max-w-3xl rounded-xl border border-white/10 bg-black/25 shadow-md backdrop-blur-md group sm:mt-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-4 py-3 text-xs font-semibold text-white marker:content-none [&::-webkit-details-marker]:hidden sm:px-5 sm:text-sm">
                        <span>Conference overview</span>
                        <svg class="h-4 w-4 shrink-0 text-primary transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="text-explanation border-t border-white/10 px-4 py-3 text-xs leading-relaxed text-white/92 sm:px-5 sm:py-4 sm:text-sm [&_a]:text-primary [&_a:hover]:underline [&_strong]:text-white [&_p]:my-1.5 [&_p:first-child]:mt-0 [&_br]:block">
                        {!! $heroIntroHtml !!}
                    </div>
                </details>
            @endif
        </div>
    </section>

    <div class="border-t border-border/80 bg-muted/25">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 grid lg:grid-cols-3 gap-6 sm:gap-8">
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                @if(!empty($topics))
                    @php $themeTrackCount = count($topics); @endphp
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03] sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Agenda</p>
                        <h2 class="mt-1 font-serif text-2xl font-bold text-foreground">Themes</h2>
                        <p class="text-explanation mt-2 text-sm text-muted-foreground">
                            This year’s programme spans {{ $themeTrackCount }} {{ Str::plural('track', $themeTrackCount) }}:
                        </p>

                        <div class="mt-6 overflow-hidden rounded-xl border border-border/80 bg-muted/35 divide-y divide-border/70">
                            @foreach($topics as $topicRow)
                                @php
                                    $sessionLeader = null;
                                    foreach ($topicRow['speakers'] ?? [] as $spk) {
                                        if (is_array($spk) && ($spk['is_session_leader'] ?? false)) {
                                            $sessionLeader = $spk;
                                            break;
                                        }
                                    }
                                    if (! $sessionLeader && ! empty($topicRow['speakers'][0]) && is_array($topicRow['speakers'][0])) {
                                        $sessionLeader = $topicRow['speakers'][0];
                                    }
                                    $avatarSrc = $sessionLeader['photo'] ?? null;
                                    $topicRowId = $topicRow['id'] ?? null;
                                @endphp
                                <div class="flex items-center gap-4 px-4 py-4 transition-colors hover:bg-background/80 sm:px-5">
                                    <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full border border-border bg-card shadow-sm">
                                        @if($avatarSrc)
                                            <img src="{{ $avatarSrc }}" alt="{{ $sessionLeader['name'] ?? 'Session leader' }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-primary/10">
                                                <svg class="h-5 w-5 text-primary/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="min-w-0 flex-1 text-sm font-semibold leading-snug text-foreground">{{ $topicRow['title'] ?? 'Topic' }}</p>

                                    @if($topicRowId)
                                        <a href="{{ route('frontend.events.topic', [$event['id'], $topicRowId]) }}"
                                           class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                                            Details
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </a>
                                    @else
                                        <span class="inline-flex shrink-0 cursor-default items-center gap-1.5 rounded-xl border border-border bg-muted px-4 py-2 text-sm font-semibold text-muted-foreground">
                                            Details
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </article>
                @elseif(!empty($event['themes']))
                    @php $themeCount = count($event['themes']); @endphp
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03] sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Agenda</p>
                        <h2 class="mt-1 font-serif text-2xl font-bold text-foreground">Themes</h2>
                        <p class="text-explanation mt-2 text-sm text-muted-foreground">
                            This year’s programme spans {{ $themeCount }} {{ Str::plural('area', $themeCount) }}:
                        </p>

                        <div class="mt-6 overflow-hidden rounded-xl border border-border/80 bg-muted/35 divide-y divide-border/70">
                            @foreach($event['themes'] as $theme)
                                @php
                                    $themeKey = mb_strtolower(trim($theme['theme'] ?? ''));
                                    $matchedTopic = $topicsByTitle[$themeKey] ?? null;
                                    $sessionLeader = null;
                                    if ($matchedTopic) {
                                        foreach ($matchedTopic['speakers'] ?? [] as $spk) {
                                            if (is_array($spk) && ($spk['is_session_leader'] ?? false)) {
                                                $sessionLeader = $spk;
                                                break;
                                            }
                                        }
                                        if (! $sessionLeader && ! empty($matchedTopic['speakers'])) {
                                            $sessionLeader = $matchedTopic['speakers'][0];
                                        }
                                    }
                                    $avatarSrc = $sessionLeader['photo'] ?? null;
                                    $topicId = $matchedTopic['id'] ?? null;
                                @endphp
                                <div class="flex items-center gap-4 px-4 py-4 transition-colors hover:bg-background/80 sm:px-5">
                                    <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full border border-border bg-card shadow-sm">
                                        @if($avatarSrc)
                                            <img src="{{ $avatarSrc }}" alt="{{ $sessionLeader['name'] ?? 'Session leader' }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-primary/10">
                                                <svg class="h-5 w-5 text-primary/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="min-w-0 flex-1 text-sm font-semibold leading-snug text-foreground">{{ $theme['theme'] ?? 'Theme' }}</p>

                                    @if($topicId)
                                        <a href="{{ route('frontend.events.topic', [$event['id'], $topicId]) }}"
                                           class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                                            Details
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </a>
                                    @else
                                        <span class="inline-flex shrink-0 cursor-default items-center gap-1.5 rounded-xl border border-border bg-muted px-4 py-2 text-sm font-semibold text-muted-foreground">
                                            Details
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif

                <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03] sm:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Schedule</p>
                    <h2 class="mt-1 font-serif text-2xl font-bold text-foreground">Programme</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($programmesSorted as $index => $programme)
                            <div class="relative overflow-hidden rounded-xl border border-border/80 bg-background/80 p-5 pl-5 sm:pl-6">
                                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-primary to-primary/40" aria-hidden="true"></div>
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="font-semibold text-foreground">{{ $programme['title'] ?? 'Session' }}</h3>
                                    <p class="text-xs font-medium tabular-nums text-muted-foreground">
                                        {{ $programme['start_time_formatted'] ?? 'Time TBA' }}
                                        @if(!empty($programme['end_time_formatted']))
                                            <span class="text-border"> · </span>{{ $programme['end_time_formatted'] }}
                                        @endif
                                    </p>
                                </div>
                                @if(!empty($programme['description']) && ! ($index === 0 && $heroFromProgramme))
                                    <div class="text-explanation mt-3 text-sm leading-relaxed text-muted-foreground prose prose-sm max-w-none dark:prose-invert [&_p]:my-2 [&_p:first-child]:mt-0">
                                        {!! $programme['description'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>

                @if(!empty($event['resources']))
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03] sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Downloads</p>
                        <h2 class="mt-1 font-serif text-2xl font-bold text-foreground">Resources</h2>
                        <ul class="mt-6 divide-y divide-border/70 rounded-xl border border-border/80 bg-muted/25">
                            @foreach($event['resources'] as $resource)
                                <li class="flex flex-col gap-1 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-5">
                                    <div class="min-w-0">
                                        <p class="font-medium text-sm text-foreground">{{ $resource['title'] ?? 'Resource' }}</p>
                                        @if(!empty($resource['description']))
                                            <p class="text-explanation mt-1 text-xs text-muted-foreground line-clamp-2">{{ $resource['description'] }}</p>
                                        @endif
                                    </div>
                                    @if(!empty($resource['url']) || !empty($resource['file_path']))
                                        <a href="{{ $resource['url'] ?? $resource['file_path'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center gap-1.5 text-sm font-semibold text-primary hover:underline">
                                            Open
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endif

                @if(!empty($event['faqs']))
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03] sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Help</p>
                        <h2 class="mt-1 font-serif text-2xl font-bold text-foreground">Frequently asked questions</h2>
                        <div class="mt-6 space-y-2">
                            @foreach($event['faqs'] as $faq)
                                <details class="group rounded-xl border border-border/80 bg-muted/30 px-4 py-3 transition-colors open:bg-background">
                                    <summary class="cursor-pointer list-none font-medium text-sm text-foreground [&::-webkit-details-marker]:hidden">
                                        <span class="flex items-center justify-between gap-2">
                                            {{ strip_tags($faq['question'] ?? 'Question') }}
                                            <svg class="h-4 w-4 shrink-0 text-muted-foreground transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                        </span>
                                    </summary>
                                    <div class="text-explanation mt-3 border-t border-border/60 pt-3 text-sm leading-relaxed text-muted-foreground">{!! $faq['answer'] ?? '' !!}</div>
                                </details>
                            @endforeach
                        </div>
                    </article>
                @endif
            </div>

            <aside class="space-y-8">
                @php
                    $keySpeakersOnly = collect($event['speakers'] ?? [])->filter(function ($s) {
                        return is_array($s) && ($s['is_key_speaker'] ?? $s['key_speaker'] ?? false);
                    })->values();
                @endphp
                @if($keySpeakersOnly->isNotEmpty())
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03]">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">People</p>
                        <h2 class="mt-1 font-serif text-xl font-bold text-foreground">Key speakers</h2>
                        <div class="mt-5 space-y-4">
                            @foreach($keySpeakersOnly as $speaker)
                                <div class="flex items-start gap-3 rounded-xl border border-border/70 bg-muted/25 p-4">
                                    <img
                                        src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}"
                                        alt="{{ $speaker['name'] ?? 'Speaker' }}"
                                        class="h-14 w-14 shrink-0 rounded-full border border-border bg-background object-cover shadow-sm"
                                    >
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold leading-snug text-foreground">{{ $speaker['name'] ?? 'Speaker' }}</p>
                                        @if(! empty($speaker['title']) || ! empty($speaker['organization']))
                                            <p class="mt-1 text-xs leading-snug text-muted-foreground">
                                                {{ $speaker['title'] ?? '' }}
                                                @if(! empty($speaker['title']) && ! empty($speaker['organization'])) · @endif
                                                {{ $speaker['organization'] ?? '' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif

                @php
                    $eventSponsors = collect($event['sponsors'] ?? [])
                        ->sortBy(fn ($row) => (int) ($row['order'] ?? 99))
                        ->values();
                @endphp
                @if($eventSponsors->isNotEmpty())
                    <article class="rounded-2xl border border-border/90 bg-card p-6 shadow-sm ring-1 ring-black/[0.03]">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Partners</p>
                        <h2 class="mt-1 font-serif text-xl font-bold text-foreground">Sponsors</h2>
                        <div class="mt-5 space-y-4">
                            @foreach($eventSponsors as $sponsor)
                                @php
                                    $rawUrl = isset($sponsor['website']) ? trim((string) $sponsor['website']) : '';
                                    $sponsorHref = '';
                                    if ($rawUrl !== '') {
                                        $sponsorHref = \Illuminate\Support\Str::startsWith($rawUrl, ['http://', 'https://'])
                                            ? $rawUrl
                                            : 'https://' . $rawUrl;
                                    }
                                @endphp
                                <div class="overflow-hidden rounded-xl border border-border/80 bg-muted/30 transition-colors hover:border-primary/35">
                                    @if($sponsorHref !== '')
                                        <a
                                            href="{{ $sponsorHref }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex flex-col gap-3 p-4 text-center hover:bg-muted/50 sm:flex-row sm:text-left"
                                        >
                                    @else
                                        <div class="flex flex-col gap-3 p-4 text-center sm:flex-row sm:text-left">
                                    @endif
                                        <div class="mx-auto flex h-16 w-28 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-border bg-background p-2 sm:mx-0">
                                            @if(!empty($sponsor['logo']))
                                                <img
                                                    src="{{ $sponsor['logo'] }}"
                                                    alt="{{ $sponsor['name'] ?? 'Sponsor logo' }}"
                                                    class="max-h-full w-full object-contain"
                                                >
                                            @else
                                                <span class="line-clamp-3 px-1 text-center text-[10px] font-medium leading-tight text-muted-foreground">{{ $sponsor['name'] ?? 'Partner' }}</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold leading-snug text-foreground">{{ $sponsor['name'] ?? 'Sponsor' }}</p>
                                            @if(!empty($sponsor['tier']))
                                                <p class="mt-1 text-xs text-muted-foreground">{{ $sponsor['tier'] }}</p>
                                            @endif
                                            @if($sponsorHref !== '')
                                                <p class="mt-2 text-xs font-semibold text-primary">Visit website →</p>
                                            @endif
                                        </div>
                                    @if($sponsorHref !== '')
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            </aside>
        </section>
    </div>
@endsection
