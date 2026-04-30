@extends('frontend.layout')

@section('title', $event['title'] ?? 'Event')

@section('content')
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <a href="{{ route('frontend.events') }}" class="inline-flex items-center gap-2 text-sm text-secondary-foreground/80 hover:text-primary transition-colors">
                Back to events
            </a>
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-6xl font-bold mt-6">{{ $event['title'] ?? 'Event' }}</h1>
            <p class="mt-4 max-w-2xl text-secondary-foreground/75">
                {{ $event['location'] ?? 'Location TBA' }} - {{ $event['start_date_formatted'] ?? 'Date TBA' }}
                @if(!empty($event['end_date_formatted'])) to {{ $event['end_date_formatted'] }} @endif
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-12 grid lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="lg:col-span-2 space-y-8">
            <article class="rounded-3xl border border-border bg-card p-8">
                <h2 class="font-serif text-2xl font-bold mb-5">Themes</h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach(($event['themes'] ?? []) as $theme)
                        <div class="flex flex-col rounded-xl bg-muted p-4">
                            <p class="font-medium">{{ $theme['theme'] ?? 'Theme' }}</p>
                            @if(!empty($theme['description']))
                                <p class="text-sm text-muted-foreground mt-1">{{ strip_tags($theme['description']) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-3xl border border-border bg-card p-8">
                <h2 class="font-serif text-2xl font-bold mb-5">Programme</h2>
                <div class="space-y-4">
                    @foreach(($event['programmes'] ?? []) as $programme)
                        <div class="rounded-xl border border-border bg-background p-5">
                            <h3 class="font-semibold">{{ $programme['title'] ?? 'Session' }}</h3>
                            @if(!empty($programme['description']))
                                <p class="text-sm text-muted-foreground mt-2">{{ $programme['description'] }}</p>
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
        </div>

        <aside class="space-y-8">
            <article class="rounded-3xl border border-border bg-card p-6">
                <h2 class="font-serif text-xl font-bold mb-4">Key Speakers & Session Leaders</h2>
                <div class="space-y-3">
                    @foreach(($event['speakers'] ?? []) as $speaker)
                        @if(($speaker['is_key_speaker'] ?? $speaker['key_speaker'] ?? false) || ($speaker['is_session_leader'] ?? $speaker['session_leader'] ?? false))
                            <div class="rounded-xl bg-muted p-3">
                                <p class="font-medium text-sm">{{ $speaker['name'] ?? 'Speaker' }}</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if(($speaker['is_key_speaker'] ?? $speaker['key_speaker'] ?? false))
                                        <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded">Key Speaker</span>
                                    @endif
                                    @if(($speaker['is_session_leader'] ?? $speaker['session_leader'] ?? false))
                                        <span class="px-2 py-0.5 bg-background text-foreground text-xs font-medium rounded border border-border">Session Leader</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </article>

            <article class="rounded-3xl border border-border bg-card p-6">
                <h2 class="font-serif text-xl font-bold mb-5">Speakers</h2>
                <div class="space-y-3">
                    @foreach(($event['speakers'] ?? []) as $speaker)
                        <div class="rounded-xl bg-muted p-4 flex items-start gap-3">
                            <img
                                src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}"
                                alt="{{ $speaker['name'] ?? 'Speaker' }}"
                                class="h-12 w-12 rounded-full object-cover border border-border"
                            >
                            <div>
                                <p class="font-medium text-sm">{{ $speaker['name'] ?? 'Speaker' }}</p>
                                <p class="text-xs text-muted-foreground mt-1">{{ $speaker['title'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            @if(!empty($event['faqs']))
                <article class="rounded-3xl border border-border bg-card p-6">
                    <h2 class="font-serif text-xl font-bold mb-5">FAQ's</h2>
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
        </aside>
    </section>
@endsection
