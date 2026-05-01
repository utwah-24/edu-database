@extends('frontend.layout')

@section('title', ($topic['title'] ?? 'Topic') . ' — ' . ($event['title'] ?? 'Event'))

@section('content')
    {{-- hero --}}
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
            <a href="{{ route('frontend.events.show', $event['id']) }}"
               class="inline-flex items-center gap-1.5 text-sm text-secondary-foreground/70 hover:text-primary transition-colors">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                Back to {{ $event['title'] ?? 'Event' }}
            </a>

            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex rounded-full bg-primary px-3 py-0.5 text-xs font-semibold text-primary-foreground">{{ $event['year'] ?? '' }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-3 leading-tight">
                {{ $topic['title'] ?? 'Topic' }}
            </h1>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14 grid lg:grid-cols-3 gap-8">

        {{-- main content --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- description --}}
            @if(!empty($topic['content']))
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-xl font-bold mb-4">About this theme</h2>
                    <div class="prose prose-sm max-w-none text-foreground/85">{!! $topic['content'] !!}</div>
                </article>
            @endif

            {{-- speakers --}}
            @if(!empty($topic['speakers']))
                <article class="rounded-3xl border border-border bg-card p-8">
                    <h2 class="font-serif text-xl font-bold mb-2">Speakers in this theme</h2>
                    <p class="text-sm text-muted-foreground mb-6">{{ count($topic['speakers']) }} {{ Str::plural('speaker', count($topic['speakers'])) }}</p>

                    <div class="space-y-4">
                        @foreach($topic['speakers'] as $speaker)
                            @php
                                $isLeader = (bool) ($speaker['is_session_leader'] ?? false);
                                $isKey    = (bool) ($speaker['is_key_speaker'] ?? false);
                            @endphp
                            <div class="flex items-start gap-4 rounded-2xl border border-border bg-background p-5">
                                {{-- avatar --}}
                                <div class="shrink-0 h-14 w-14 rounded-full overflow-hidden border border-border bg-muted">
                                    @if(!empty($speaker['photo']))
                                        <img src="{{ $speaker['photo'] }}" alt="{{ $speaker['name'] ?? '' }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <svg class="h-6 w-6 text-muted-foreground/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        @if($isLeader)
                                            <span class="inline-flex rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">Session leader</span>
                                        @endif
                                        @if($isKey)
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Key speaker</span>
                                        @endif
                                        @if(!$isLeader && !$isKey)
                                            <span class="inline-flex rounded-full bg-muted px-2.5 py-0.5 text-xs font-medium text-muted-foreground border border-border">Theme speaker</span>
                                        @endif
                                    </div>
                                    <p class="font-semibold text-foreground leading-snug">{{ $speaker['name'] ?? 'Speaker' }}</p>
                                    <p class="text-sm text-muted-foreground mt-0.5">
                                        {{ $speaker['title'] ?? '' }}
                                        @if(!empty($speaker['organization'])) — {{ $speaker['organization'] }} @endif
                                    </p>
                                    @if(!empty($speaker['bio']) && strip_tags($speaker['bio']) !== 'null')
                                        <p class="text-sm text-foreground/75 mt-2 leading-relaxed line-clamp-3">{{ strip_tags($speaker['bio']) }}</p>
                                    @endif

                                    {{-- contact links --}}
                                    @if(!empty($speaker['email']) || !empty($speaker['linkedin']))
                                        <div class="flex items-center gap-3 mt-3">
                                            @if(!empty($speaker['email']))
                                                <a href="mailto:{{ $speaker['email'] }}" class="text-xs text-primary hover:underline font-medium">Email</a>
                                            @endif
                                            @if(!empty($speaker['linkedin']))
                                                <a href="https://linkedin.com/in/{{ $speaker['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary hover:underline font-medium">LinkedIn</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endif

        </div>

        {{-- sidebar --}}
        <aside class="space-y-6">
            <article class="rounded-3xl border border-border bg-card p-6">
                <h2 class="font-serif text-lg font-bold mb-4">Event</h2>
                <div class="space-y-2 text-sm text-muted-foreground">
                    <p class="font-semibold text-foreground">{{ $event['title'] ?? '' }}</p>
                    @if(!empty($event['location']))
                        <p class="flex items-start gap-1.5">
                            <svg class="h-4 w-4 shrink-0 text-primary mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                            {{ $event['location'] }}
                        </p>
                    @endif
                    @if(!empty($topic['topic_date']))
                        <p class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                            {{ \Carbon\Carbon::parse($topic['topic_date'])->format('F j, Y') }}
                        </p>
                    @endif
                </div>
                <a href="{{ route('frontend.events.show', $event['id']) }}"
                   class="mt-5 inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground hover:bg-primary/90 transition-colors">
                    View full event
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </a>
            </article>

            @if(!empty($topic['speakers']))
                <article class="rounded-3xl border border-border bg-card p-6">
                    <h2 class="font-serif text-lg font-bold mb-4">{{ count($topic['speakers']) }} {{ Str::plural('Speaker', count($topic['speakers'])) }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($topic['speakers'] as $spk)
                            <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-background shadow-sm bg-muted"
                                 title="{{ $spk['name'] ?? '' }}">
                                @if(!empty($spk['photo']))
                                    <img src="{{ $spk['photo'] }}" alt="{{ $spk['name'] ?? '' }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-primary/10">
                                        <svg class="h-4 w-4 text-primary/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>
            @endif
        </aside>

    </div>
@endsection
