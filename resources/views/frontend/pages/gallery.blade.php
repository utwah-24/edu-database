@extends('frontend.layout')

@section('title', 'Gallery')

@section('content')
    <section class="bg-secondary text-secondary-foreground">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6">Gallery</h1>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($items as $index => $item)
                @php
                    $img = $item['url'] ?? $item['image_path'] ?? null;
                @endphp
                @if($img)
                    <a href="{{ $img }}" target="_blank" rel="noopener noreferrer" class="group block overflow-hidden rounded-2xl border border-border bg-card hover:border-primary/40 hover:shadow-lg transition-all">
                        <div class="aspect-[4/3]">
                            <img src="{{ $img }}" alt="Gallery image {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </section>
@endsection
