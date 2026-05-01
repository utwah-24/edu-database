@extends('frontend.layout')

@section('title', 'Topics')

@section('content')
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6">All Topic Tracks</h1>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($topics as $topic)
                <article class="rounded-3xl border border-border bg-card p-6">
                    @if(!empty($topic['topic_picture']))
                        <img src="{{ $topic['topic_picture'] }}" alt="{{ $topic['title'] ?? 'Topic' }}" class="w-full h-44 object-cover rounded-xl mb-4">
                    @endif
                    <p class="text-xs px-2 py-1 rounded-full bg-primary/10 text-primary inline-flex">{{ $topic['event']['year'] ?? '' }}</p>
                    <h2 class="font-serif text-xl font-bold mt-3">{{ $topic['title'] ?? 'Topic' }}</h2>
                    @if(!empty($topic['topic_date_formatted']))
                        <p class="text-xs text-muted-foreground mt-1">{{ $topic['topic_date_formatted'] }}</p>
                    @endif
                    <p class="text-muted-foreground mt-2 text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($topic['content'] ?? ''), 180) }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
