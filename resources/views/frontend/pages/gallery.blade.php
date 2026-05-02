@extends('frontend.layout')

@section('title', 'Gallery')

@section('content')
    <section class="bg-white text-foreground border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6 text-foreground">Gallery</h1>
            <p class="mt-4 max-w-2xl text-sm text-muted-foreground">Photos grouped by conference. Each album covers one event edition.</p>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10 pb-16 space-y-14 sm:space-y-16">
        @forelse($galleriesByEvent as $block)
            @php
                $eidBlock = $block['event_id'] ?? null;
                $blockTitle = $block['title'] ?? 'Event';
                $year = $block['year'] ?? null;
                $location = trim((string) ($block['location'] ?? ''));
            @endphp
            <div>
                <div class="mb-6 pb-5 border-b border-border">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-foreground leading-tight">{{ $blockTitle }}</h2>
                            <p class="mt-2 text-sm text-muted-foreground">
                                @if($year !== null && $year !== '')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ $year }}</span>
                                @endif
                                @if($year !== null && $year !== '' && $location !== '')
                                    <span class="mx-1.5 text-border">·</span>
                                @endif
                                @if($location !== '')
                                    {{ $location }}
                                @elseif($year === null || $year === '')
                                    <span>Conference gallery</span>
                                @endif
                            </p>
                        </div>
                        @if($eidBlock)
                            <a href="{{ route('frontend.events.show', $eidBlock) }}" class="text-sm font-medium text-primary hover:underline shrink-0">View event details →</a>
                        @endif
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($block['items'] ?? [] as $idx => $item)
                        @if(! is_array($item))
                            @continue
                        @endif
                        @php $img = $item['url'] ?? $item['image_path'] ?? null; @endphp
                        @if($img)
                            <a
                                href="{{ $img }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="group block overflow-hidden rounded-2xl border border-border bg-card hover:border-primary/40 hover:shadow-lg transition-all"
                                title="{{ $blockTitle }} — image {{ $idx + 1 }}"
                            >
                                <div class="aspect-[4/3]">
                                    <img
                                        src="{{ $img }}"
                                        alt="{{ $blockTitle }}, gallery photo {{ $idx + 1 }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-14 text-center text-muted-foreground text-sm">
                No gallery photos are published yet for any event.
            </div>
        @endforelse
    </section>
@endsection
