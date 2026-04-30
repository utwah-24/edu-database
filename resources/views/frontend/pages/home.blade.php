@extends('frontend.layout')

@section('title', 'Event Archive')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="/storage/Bg.jpeg" alt="Conference background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-secondary/90"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 md:py-28">
            <span class="text-primary font-medium text-sm tracking-wide uppercase">Conference History</span>
            <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight tracking-tight mt-4">
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

    @if(!empty($event))
        <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10" id="current-event">
            <div class="grid lg:grid-cols-2 gap-8">
                <article class="rounded-3xl border border-border bg-card overflow-hidden">
                    <div class="h-56 bg-muted">
                        <img
                            src="{{ $event['cover_image_url'] ?? ($event['cover_image'] ?? '/placeholder.jpg') }}"
                            alt="{{ $event['title'] ?? 'Current event' }}"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="p-6">
                        <p class="text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary inline-flex">{{ $event['year'] ?? 'Current' }}</p>
                        <h2 class="font-serif text-2xl font-bold mt-3">{{ $event['title'] ?? 'Current Event' }}</h2>
                            <p class="text-muted-foreground mt-2">{{ $event['location'] ?? 'Location TBA' }}</p>
                            <p class="text-muted-foreground text-sm mt-1">
                                {{ $event['start_date_formatted'] ?? 'Date TBA' }}
                                @if(!empty($event['end_date_formatted'])) - {{ $event['end_date_formatted'] }} @endif
                            </p>
                        <a href="{{ route('frontend.events.show', $event['id']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors mt-4">
                            View Event Details
                        </a>
                    </div>
                </article>

                <div class="grid sm:grid-cols-2 gap-4">
                    <article class="rounded-2xl border border-border bg-card p-5">
                        <p class="text-muted-foreground text-xs">Speakers</p>
                        <p class="font-serif text-3xl font-bold mt-2">{{ count($event['speakers'] ?? []) }}</p>
                    </article>
                    <article class="rounded-2xl border border-border bg-card p-5">
                        <p class="text-muted-foreground text-xs">Sessions</p>
                        <p class="font-serif text-3xl font-bold mt-2">{{ count($event['programmes'] ?? []) }}</p>
                    </article>
                    <article class="rounded-2xl border border-border bg-card p-5">
                        <p class="text-muted-foreground text-xs">Resources</p>
                        <p class="font-serif text-3xl font-bold mt-2">{{ count($event['resources'] ?? []) }}</p>
                    </article>
                    <article class="rounded-2xl border border-border bg-card p-5">
                        <p class="text-muted-foreground text-xs">Themes</p>
                        <p class="font-serif text-3xl font-bold mt-2">{{ count($event['themes'] ?? []) }}</p>
                    </article>
                </div>
            </div>
        </section>
    @endif

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

    <section id="topics" class="py-14 sm:py-20 bg-secondary text-secondary-foreground">
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
                    <article class="group p-6 rounded-2xl bg-sidebar border border-sidebar-border transition-all">
                        <h3 class="font-serif text-xl font-semibold text-sidebar-foreground group-hover:text-primary transition-colors">
                            {{ $topic['title'] ?? 'Topic' }}
                        </h3>
                        <p class="text-sidebar-foreground/60 text-sm leading-relaxed mt-3 line-clamp-4">
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
