@extends('frontend.layout')

@section('title', 'Resources')

@section('content')
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-6xl font-bold mt-6">Resources</h1>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="space-y-4">
            @foreach($resources as $resource)
                <article class="group bg-card rounded-2xl border border-border p-6 hover:shadow-lg hover:border-primary/30 transition-all">
                    <h2 class="font-serif text-xl font-semibold text-foreground group-hover:text-primary transition-colors">{{ $resource['title'] ?? 'Resource' }}</h2>
                    @if(!empty($resource['description']))
                        <p class="text-muted-foreground text-sm mt-2">{{ $resource['description'] }}</p>
                    @endif
                    @if(!empty($resource['url']) || !empty($resource['file_path']))
                        <a href="{{ $resource['url'] ?? $resource['file_path'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors mt-4">
                            Open resource
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    </section>
@endsection
