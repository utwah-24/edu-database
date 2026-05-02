@extends('frontend.layout')

@section('title', 'Resources')

@section('content')
    <section class="bg-white text-foreground border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6 text-foreground">Resources</h1>
            <p class="mt-4 text-muted-foreground text-sm max-w-2xl">
                Materials by conference edition. Filter by event or search by resource title, description, file type, event, year, or venue.
            </p>
            @if(!empty($resourcesByEvent))
                <div class="mt-10 flex flex-col gap-5 md:flex-row md:flex-wrap md:items-end">
                    <div class="w-full md:w-auto md:min-w-[220px] lg:min-w-[260px]">
                        <label for="resources-filter-event" class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-2">Event</label>
                        <select
                            id="resources-filter-event"
                            data-resources-filter-event
                            class="w-full rounded-xl border border-border bg-card px-3 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40"
                        >
                            <option value="">All events</option>
                            @foreach($eventsForResourceFilters ?? [] as $evt)
                                @if(!empty($evt['id']))
                                    <option value="{{ $evt['id'] }}">{{ $evt['label'] ?? $evt['title'] ?? 'Event' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:flex-1 md:min-w-[280px]">
                        <label for="resources-search" class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-2">Search</label>
                        <input
                            id="resources-search"
                            type="search"
                            data-resources-search
                            autocomplete="off"
                            placeholder="Title, description, event, year, location…"
                            class="w-full rounded-xl border border-border bg-card px-3 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40"
                        >
                    </div>
                </div>
            @endif
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10 pb-16">
        @if(empty($resourcesByEvent))
            <div class="rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-14 text-center text-muted-foreground text-sm">
                No resources are published yet for any conference.
            </div>
        @else
            <p
                id="resources-empty-filtered"
                class="rounded-2xl border border-border bg-muted/30 px-6 py-12 text-center text-muted-foreground text-sm hidden"
                role="status"
                hidden
            >
                No resources match the current event filter or search.
            </p>
            <div id="resources-grouped-wrap" data-resources-grouped-wrap class="space-y-14 sm:space-y-16">
                @foreach($resourcesByEvent as $block)
                    @php
                        $eid = $block['event_id'] ?? '';
                        $blockTitle = $block['title'] ?? 'Event';
                        $year = $block['year'] ?? null;
                        $loc = trim((string) ($block['location'] ?? ''));
                    @endphp
                    <div
                        class="resources-event-block space-y-6"
                        data-resource-event-block
                        data-event-id="{{ e($eid) }}"
                    >
                        <div class="border-b border-border pb-5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-foreground">{{ $blockTitle }}</h2>
                                    <p class="mt-2 text-sm text-muted-foreground">
                                        @if($year !== null && $year !== '')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ $year }}</span>
                                        @endif
                                        @if(($year !== null && $year !== '') && $loc !== '')
                                            <span class="mx-1.5 text-border">·</span>
                                        @endif
                                        @if($loc !== '')
                                            {{ $loc }}
                                        @endif
                                    </p>
                                </div>
                                @if($eid !== '')
                                    <a href="{{ route('frontend.events.show', $eid) }}" class="text-sm font-medium text-primary hover:underline shrink-0">Event page →</a>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-4" data-resource-cards-in-block>
                            @foreach($block['items'] ?? [] as $resource)
                                @if(! is_array($resource))
                                    @continue
                                @endif
                                @php
                                    $href = $resource['url'] ?? $resource['file_path'] ?? null;
                                    $searchNorm = $resource['_filter_search_normalized'] ?? '';
                                @endphp
                                <article
                                    class="group resource-card bg-card rounded-2xl border border-border p-6 hover:shadow-lg hover:border-primary/30 transition-all"
                                    data-resource-card
                                    data-search="{{ e($searchNorm) }}"
                                >
                                    <h3 class="font-serif text-xl font-semibold text-foreground group-hover:text-primary transition-colors">{{ $resource['title'] ?? 'Resource' }}</h3>
                                    @if(!empty($resource['file_type']))
                                        <p class="text-xs text-muted-foreground mt-1 uppercase tracking-wide">{{ $resource['file_type'] }}</p>
                                    @endif
                                    @if(!empty($resource['description']))
                                        <p class="text-muted-foreground text-sm mt-2">{{ strip_tags($resource['description']) }}</p>
                                    @endif
                                    @if(filled($href))
                                        <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors mt-4">
                                            Open resource
                                        </a>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    @if(!empty($resourcesByEvent))
        <script>
            (function () {
                const wrap = document.getElementById('resources-grouped-wrap');
                const emptyHint = document.getElementById('resources-empty-filtered');
                const eventSelect = document.querySelector('[data-resources-filter-event]');
                const searchInput = document.querySelector('[data-resources-search]');
                if (!wrap || !eventSelect || !searchInput) {
                    return;
                }

                const blocks = [...wrap.querySelectorAll('[data-resource-event-block]')];

                const normalizeQuery = function (raw) {
                    return String(raw || '')
                        .trim()
                        .toLowerCase()
                        .replace(/\s+/g, ' ');
                };

                const applyFilters = function () {
                    const eventVal = eventSelect.value;
                    const needle = normalizeQuery(searchInput.value);

                    let anyBlockVisible = false;

                    blocks.forEach(function (block) {
                        const eid = block.dataset.eventId || '';
                        const cards = [...block.querySelectorAll('[data-resource-card]')];

                        if (eventVal && String(eid) !== String(eventVal)) {
                            block.classList.add('hidden');
                            block.toggleAttribute('hidden', true);
                            return;
                        }

                        let visibleInBlock = 0;

                        cards.forEach(function (card) {
                            const hay = normalizeQuery(card.dataset.search || '');
                            let show = true;
                            if (needle !== '' && !hay.includes(needle)) {
                                show = false;
                            }
                            card.classList.toggle('hidden', !show);
                            card.toggleAttribute('hidden', !show);
                            if (show) {
                                visibleInBlock += 1;
                            }
                        });

                        const showBlock = visibleInBlock > 0;
                        block.classList.toggle('hidden', !showBlock);
                        block.toggleAttribute('hidden', !showBlock);
                        if (showBlock) {
                            anyBlockVisible = true;
                        }
                    });

                    if (emptyHint) {
                        const showEmpty = !anyBlockVisible;
                        emptyHint.classList.toggle('hidden', !showEmpty);
                        emptyHint.toggleAttribute('hidden', !showEmpty);
                        wrap.classList.toggle('hidden', showEmpty);
                        wrap.toggleAttribute('hidden', showEmpty);
                    }
                };

                eventSelect.addEventListener('change', applyFilters);
                searchInput.addEventListener('input', applyFilters);
                applyFilters();
            })();
        </script>
    @endif
@endsection
