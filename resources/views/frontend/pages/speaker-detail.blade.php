@extends('frontend.layout')

@section('title', $speaker['name'] ?? 'Speaker')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <a href="{{ route('frontend.home') }}#speakers" class="inline-flex items-center gap-2 text-sm text-primary hover:underline">
            ← Back to home
        </a>
        <a href="{{ route('frontend.speakers') }}" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary ml-4 hover:underline">
            All speakers
        </a>

        <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,280px)_1fr] lg:gap-14">
            <div class="text-center lg:text-left">
                <div class="mx-auto lg:mx-0 relative h-40 w-40 shrink-0 overflow-hidden rounded-full bg-muted shadow-lg ring-2 ring-border inline-block align-top">
                    <img
                        src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}"
                        alt="{{ $speaker['name'] ?? 'Speaker' }}"
                        class="w-full h-full object-cover"
                    >
                </div>
                <h1 class="font-serif text-3xl font-bold text-foreground mt-6">{{ $speaker['name'] ?? 'Speaker' }}</h1>
                @if(!empty($speaker['title']) || !empty($speaker['organization']))
                    <p class="text-muted-foreground mt-2">
                        {{ $speaker['title'] ?? '' }}
                        @if(!empty($speaker['title']) && !empty($speaker['organization'])) · @endif
                        {{ $speaker['organization'] ?? '' }}
                    </p>
                @endif

                @if(($speakerKinds ?? []) !== [])
                    <div class="mt-4 flex flex-wrap justify-center lg:justify-start gap-2">
                        @foreach($speakerKinds as $label)
                            <span class="inline-flex px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold border border-primary/25">{{ $label }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-8 min-w-0">
                @if(isset($event['id']))
                    <article class="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <h2 class="font-serif text-xl font-bold text-foreground">Associated event</h2>
                        <p class="text-lg font-semibold text-foreground mt-3">{{ $event['title'] ?? 'Conference' }}</p>
                        @if(isset($event['year']))
                            <p class="text-sm text-muted-foreground mt-1">{{ $event['year'] }}</p>
                        @endif
                        @if(!empty($event['start_date_formatted']) || !empty($event['location']))
                            <p class="text-sm text-muted-foreground mt-2">
                                @if(!empty($event['location'])){{ $event['location'] }}@endif
                                @if(!empty($event['location']) && !empty($event['start_date_formatted'])) · @endif
                                {{ $event['start_date_formatted'] ?? '' }}
                                @if(!empty($event['end_date_formatted'])) – {{ $event['end_date_formatted'] }} @endif
                            </p>
                        @endif
                        @if(isset($event['id']))
                            <a href="{{ route('frontend.events.show', $event['id']) }}" class="inline-flex mt-5 text-sm font-medium text-primary hover:underline">
                                View full event →
                            </a>
                        @endif
                    </article>
                @endif

                @if(isset($topicSummary['id']))
                    <article class="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <h2 class="font-serif text-xl font-bold text-foreground">Associated topic / theme</h2>
                        <p class="text-foreground font-semibold mt-3">{{ $topicSummary['title'] ?? 'Topic' }}</p>

                        @php
                            $canonicalTopicTitle = mb_strtolower(trim((string) ($topicSummary['title'] ?? '')));
                            $canonicalTheme = ! empty($matchedConferenceTheme) ? mb_strtolower(trim($matchedConferenceTheme)) : '';
                            $showSeparateThemeNote = ($canonicalTheme !== '' && $canonicalTheme !== $canonicalTopicTitle);
                        @endphp
                        @if($showSeparateThemeNote)
                            <p class="text-xs uppercase tracking-wide text-muted-foreground mt-2 font-medium">
                                Related conference theme
                            </p>
                            <p class="text-sm text-foreground mt-1">{{ $matchedConferenceTheme }}</p>
                        @endif

                        @if(!empty($topicSummary['topic_date_formatted'] ?? ''))
                            <p class="text-sm text-muted-foreground mt-2">{{ $topicSummary['topic_date_formatted'] }}</p>
                        @endif

                        @if(!empty($topicSummary['topic_picture'] ?? ''))
                            <div class="mt-4 overflow-hidden rounded-xl border border-border max-w-md bg-muted">
                                <img src="{{ $topicSummary['topic_picture'] }}" alt="" class="w-full h-auto object-cover max-h-48">
                            </div>
                        @endif

                        @php
                            $topicBlurb = trim(strip_tags($topicSummary['content'] ?? ''));
                        @endphp
                        @if($topicBlurb !== '')
                            <p class="text-sm text-muted-foreground leading-relaxed mt-4">
                                {{ \Illuminate\Support\Str::limit($topicBlurb, 360) }}
                            </p>
                        @endif

                        @php $eidTopic = $topicSummary['event_id'] ?? ($event['id'] ?? null); $tid = $topicSummary['id'] ?? null; @endphp
                        @if($eidTopic && $tid)
                            <a href="{{ route('frontend.events.topic', [$eidTopic, $tid]) }}" class="inline-flex mt-5 text-sm font-medium text-primary hover:underline">
                                Open full topic / theme page →
                            </a>
                        @endif
                    </article>
                @endif

                @if(!empty($speaker['bio']))
                    <article class="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <h2 class="font-serif text-xl font-bold text-foreground">About</h2>
                        <div class="prose prose-sm max-w-none text-muted-foreground mt-4 dark:prose-invert [&_p]:my-2 [&_p:first-child]:mt-0">{!! $speaker['bio'] !!}</div>
                    </article>
                @endif

                @if(!empty($speaker['email']) || !empty($speaker['linkedin']) || !empty($speaker['twitter']))
                    <div class="rounded-2xl border border-border bg-muted/40 p-6 flex flex-wrap gap-4">
                        @if(!empty($speaker['email']))
                            <a href="mailto:{{ $speaker['email'] }}" class="text-sm text-primary font-medium hover:underline">Email</a>
                        @endif
                        @if(!empty($speaker['linkedin']))
                            <a href="{{ $speaker['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-sm text-primary font-medium hover:underline">LinkedIn</a>
                        @endif
                        @if(!empty($speaker['twitter']))
                            <a href="{{ $speaker['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-sm text-primary font-medium hover:underline">Twitter / X</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
