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

    <section class="event-detail-hero overflow-hidden text-white">
        <div class="event-detail-hero__media">
            @if($heroCoverSrc !== '')
                <img src="{{ $heroCoverSrc }}" alt="" decoding="async">
            @else
                <div class="h-full w-full bg-secondary"></div>
            @endif
        </div>
        <div class="event-detail-hero__overlay" aria-hidden="true"></div>
        <div class="event-detail-hero__veil" aria-hidden="true"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <a href="{{ route('frontend.events') }}" class="inline-flex items-center gap-2 text-sm text-white/80 hover:text-primary transition-colors">
                Back to events
            </a>
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6 text-white drop-shadow-sm">{{ $event['title'] ?? 'Event' }}</h1>
            <p class="mt-4 max-w-2xl text-white/85">
                {{ $event['location'] ?? 'Location TBA' }} — {{ $event['start_date_formatted'] ?? 'Date TBA' }}
                @if(!empty($event['end_date_formatted'])) to {{ $event['end_date_formatted'] }} @endif
            </p>

            @if($heroIntroHtml !== '')
                <div class="mt-6 max-w-3xl text-sm sm:text-base leading-relaxed text-white/90 [&_a]:text-primary [&_a:hover]:underline [&_strong]:text-white [&_p]:my-2 [&_p:first-child]:mt-0 [&_br]:block">
                    {!! $heroIntroHtml !!}
                </div>
            @endif
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-12 grid lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="lg:col-span-2 space-y-8">
            {{--
              Topic tracks (conference themes) — sourced from GET /api/topics?event_id=…
              The nested event.themes relation often has fewer rows than topics.
            --}}
            @if(!empty($topics))
                @php $themeTrackCount = count($topics); @endphp
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-2xl font-bold flex items-center gap-2 mb-2">
                        <svg class="h-5 w-5 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                        </svg>
                        Themes
                    </h2>
                    <p class="text-sm text-muted-foreground mb-6">
                        This year's key discussions centred on {{ $themeTrackCount }} critical {{ Str::plural('area', $themeTrackCount) }}:
                    </p>

                    <div class="rounded-2xl bg-muted/50 border border-border/60 overflow-hidden divide-y divide-border/60">
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
                            <div class="flex items-center gap-4 px-5 py-4">
                                <div class="shrink-0 h-11 w-11 rounded-full overflow-hidden bg-muted border border-border shadow-sm">
                                    @if($avatarSrc)
                                        <img src="{{ $avatarSrc }}" alt="{{ $sessionLeader['name'] ?? 'Session leader' }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-primary/10">
                                            <svg class="h-5 w-5 text-primary/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                        </div>
                                    @endif
                                </div>

                                <p class="flex-1 font-semibold text-foreground text-sm leading-snug">{{ $topicRow['title'] ?? 'Topic' }}</p>

                                @if($topicRowId)
                                    <a href="{{ route('frontend.events.topic', [$event['id'], $topicRowId]) }}"
                                       class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                                        Details
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                    </a>
                                @else
                                    <span class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-muted border border-border px-4 py-2 text-sm font-semibold text-muted-foreground cursor-default">
                                        Details
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>
            @elseif(!empty($event['themes']))
                {{-- Fallback: legacy event_themes rows only (no topics on API) --}}
                @php $themeCount = count($event['themes']); @endphp
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-2xl font-bold flex items-center gap-2 mb-2">
                        <svg class="h-5 w-5 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                        </svg>
                        Themes
                    </h2>
                    <p class="text-sm text-muted-foreground mb-6">
                        This year's key discussions centred on {{ $themeCount }} critical {{ Str::plural('area', $themeCount) }}:
                    </p>

                    <div class="rounded-2xl bg-muted/50 border border-border/60 overflow-hidden divide-y divide-border/60">
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
                            <div class="flex items-center gap-4 px-5 py-4">
                                <div class="shrink-0 h-11 w-11 rounded-full overflow-hidden bg-muted border border-border shadow-sm">
                                    @if($avatarSrc)
                                        <img src="{{ $avatarSrc }}" alt="{{ $sessionLeader['name'] ?? 'Session leader' }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-primary/10">
                                            <svg class="h-5 w-5 text-primary/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                        </div>
                                    @endif
                                </div>

                                <p class="flex-1 font-semibold text-foreground text-sm leading-snug">{{ $theme['theme'] ?? 'Theme' }}</p>

                                @if($topicId)
                                    <a href="{{ route('frontend.events.topic', [$event['id'], $topicId]) }}"
                                       class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                                        Details
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                    </a>
                                @else
                                    <span class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-muted border border-border px-4 py-2 text-sm font-semibold text-muted-foreground cursor-default">
                                        Details
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>
            @endif

            <article class="rounded-3xl border border-border bg-card p-8">
                <h2 class="font-serif text-2xl font-bold mb-5">Programme</h2>
                <div class="space-y-4">
                    @foreach($programmesSorted as $index => $programme)
                        <div class="rounded-xl border border-border bg-background p-5">
                            <h3 class="font-semibold">{{ $programme['title'] ?? 'Session' }}</h3>
                            @if(!empty($programme['description']) && ! ($index === 0 && $heroFromProgramme))
                                <div class="mt-2 text-sm text-muted-foreground prose prose-sm max-w-none dark:prose-invert [&_p]:my-2 [&_p:first-child]:mt-0">
                                    {!! $programme['description'] !!}
                                </div>
                            @endif
                            <p class="text-xs text-muted-foreground mt-2">
                                {{ $programme['start_time_formatted'] ?? 'Time TBA' }}
                                @if(!empty($programme['end_time_formatted'])) - {{ $programme['end_time_formatted'] }} @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </article>

            @if(!empty($event['resources']))
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-2xl font-bold mb-5">Resources</h2>
                    <div class="space-y-3">
                        @foreach($event['resources'] as $resource)
                            <div class="rounded-xl bg-muted p-4">
                                <p class="font-medium text-sm">{{ $resource['title'] ?? 'Resource' }}</p>
                                @if(!empty($resource['description']))
                                    <p class="text-xs text-muted-foreground mt-1">{{ $resource['description'] }}</p>
                                @endif
                                @if(!empty($resource['url']) || !empty($resource['file_path']))
                                    <a href="{{ $resource['url'] ?? $resource['file_path'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs text-primary mt-2 hover:underline">
                                        Open resource
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>
            @endif

            @if(!empty($event['faqs']))
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-2xl font-bold mb-5">FAQ's</h2>
                    <div class="space-y-3">
                        @foreach($event['faqs'] as $faq)
                            <details class="group rounded-xl border border-border bg-muted/40 px-4 py-3">
                                <summary class="cursor-pointer font-medium text-sm">{{ strip_tags($faq['question'] ?? 'Question') }}</summary>
                                <div class="mt-2 text-sm text-muted-foreground">{!! $faq['answer'] ?? '' !!}</div>
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
                <article class="rounded-3xl border border-border bg-card p-6">
                    <h2 class="font-serif text-xl font-bold mb-4">Key Speakers</h2>
                    <div class="space-y-4">
                        @foreach($keySpeakersOnly as $speaker)
                            <div class="rounded-xl bg-muted p-4 flex items-start gap-3">
                                <img
                                    src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}"
                                    alt="{{ $speaker['name'] ?? 'Speaker' }}"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover border border-border bg-background"
                                >
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-sm text-foreground leading-snug">{{ $speaker['name'] ?? 'Speaker' }}</p>
                                    @if(! empty($speaker['title']) || ! empty($speaker['organization']))
                                        <p class="text-xs text-muted-foreground mt-1 leading-snug">
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
                <article class="rounded-3xl border border-border bg-card p-6">
                    <h2 class="font-serif text-xl font-bold mb-5">Sponsors</h2>
                    <div class="space-y-4">
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
                            <div class="rounded-xl border border-border bg-muted/60 overflow-hidden transition-colors hover:border-primary/35">
                                @if($sponsorHref !== '')
                                    <a
                                        href="{{ $sponsorHref }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex flex-col gap-3 p-4 text-center sm:text-left hover:bg-muted"
                                    >
                                @else
                                    <div class="flex flex-col gap-3 p-4 text-center sm:text-left">
                                @endif
                                    <div class="mx-auto sm:mx-0 shrink-0 h-16 w-28 rounded-lg bg-background border border-border flex items-center justify-center overflow-hidden p-2">
                                        @if(!empty($sponsor['logo']))
                                            <img
                                                src="{{ $sponsor['logo'] }}"
                                                alt="{{ $sponsor['name'] ?? 'Sponsor logo' }}"
                                                class="max-h-full w-full object-contain"
                                            >
                                        @else
                                            <span class="text-[10px] font-medium text-muted-foreground text-center leading-tight px-1 line-clamp-3">{{ $sponsor['name'] ?? 'Partner' }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-sm text-foreground leading-snug">{{ $sponsor['name'] ?? 'Sponsor' }}</p>
                                        @if(!empty($sponsor['tier']))
                                            <p class="text-xs text-muted-foreground mt-1">{{ $sponsor['tier'] }}</p>
                                        @endif
                                        @if($sponsorHref !== '')
                                            <p class="text-xs font-medium text-primary mt-2">Visit website →</p>
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
@endsection
