@extends('frontend.layout')

@section('title', 'Speakers')

@section('content')
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-6xl font-bold mt-6">All Speakers</h1>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($speakers as $speaker)
                <article class="bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:border-primary/30 transition-all duration-300">
                    <div class="p-5 space-y-4 text-sm">
                        <div class="flex justify-center">
                            <div class="relative h-28 w-28 shrink-0 overflow-hidden rounded-full bg-muted shadow-md ring-2 ring-border">
                                <img
                                    src="{{ $speaker['photo'] ?? '/placeholder-user.jpg' }}"
                                    alt="{{ $speaker['name'] ?? 'Speaker' }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        </div>
                        <h2 class="font-serif text-xl font-bold text-foreground leading-snug">{{ $speaker['name'] ?? 'Speaker' }}</h2>
                        <p class="text-muted-foreground text-sm mt-1">{{ $speaker['title'] ?? '' }} {{ !empty($speaker['organization']) ? ' · ' . $speaker['organization'] : '' }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
