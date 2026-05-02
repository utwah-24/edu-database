@extends('frontend.layout')

@section('title', 'All Events')

@section('content')
    <section class="bg-white text-foreground border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-4 text-foreground">Explore All Past Events</h1>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($events as $event)
                <article class="group overflow-hidden rounded-3xl border border-border bg-card hover:shadow-lg hover:border-primary/40 transition-all">
                    <div class="relative h-52">
                        <img src="{{ $event['cover_image_url'] ?? ($event['cover_image'] ?? '/placeholder.jpg') }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
                    </div>

                    <div class="p-6">
                        <h2 class="font-serif text-2xl font-bold text-foreground group-hover:text-primary transition-colors">{{ $event['title'] }}</h2>
                        <div class="mt-3 space-y-2 text-sm text-muted-foreground">
                            <p>{{ $event['start_date_formatted'] ?? 'Date TBA' }} @if(!empty($event['end_date_formatted'])) - {{ $event['end_date_formatted'] }} @endif</p>
                            @if(!empty($event['location']))
                                <p>{{ $event['location'] }}</p>
                            @endif
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary">{{ $event['year'] }}</span>
                            <a href="{{ route('frontend.events.show', $event['id']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors">View Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
