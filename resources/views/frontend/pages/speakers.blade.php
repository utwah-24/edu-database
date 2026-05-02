@extends('frontend.layout')

@section('title', 'Speakers')

@section('content')
    <section class="bg-white text-foreground border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 lg:py-20">
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold mt-6 text-foreground">All Speakers</h1>
            <p class="mt-4 text-muted-foreground text-sm max-w-2xl">
                Filter by conference event and theme, or search across names, titles, organisations, topics, themes, and events.
            </p>
            <div class="mt-10 flex flex-col gap-5 xl:flex-row xl:flex-wrap xl:items-end">
                <div class="w-full xl:w-auto xl:min-w-[220px]">
                    <label for="speakers-filter-event" class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-2">Event</label>
                    <select
                        id="speakers-filter-event"
                        data-speakers-filter-event
                        class="w-full rounded-xl border border-border bg-card px-3 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40"
                    >
                        <option value="">All events</option>
                        @foreach($eventsForSpeakerFilters as $evt)
                            @if(isset($evt['id']))
                                <option value="{{ $evt['id'] }}">{{ $evt['label'] ?? $evt['title'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="w-full xl:w-auto xl:min-w-[220px]">
                    <label for="speakers-filter-theme" class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-2">Theme</label>
                    <select
                        id="speakers-filter-theme"
                        data-speakers-filter-theme
                        disabled
                        aria-disabled="true"
                        class="w-full rounded-xl border border-border bg-card px-3 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <option value="">All themes — select an event first</option>
                    </select>
                </div>
                <div class="w-full xl:flex-1 xl:min-w-[280px]">
                    <label for="speakers-search" class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-2">Search</label>
                    <input
                        id="speakers-search"
                        type="search"
                        data-speakers-search
                        autocomplete="off"
                        placeholder="Name, title, organisation, theme, topic, event…"
                        class="w-full rounded-xl border border-border bg-card px-3 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40"
                    >
                </div>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 sm:mt-10 pb-16">
        <p
            id="speakers-empty-hint"
            class="rounded-2xl border border-border bg-muted/30 px-6 py-12 text-center text-muted-foreground hidden"
            role="status"
            hidden
        >
            No speakers match the current filters. Try broadening event, theme, or search.
        </p>
        <div id="speakers-grid" data-speakers-grid class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($speakers as $speaker)
                <article
                    class="bg-card rounded-2xl border border-border overflow-hidden hover:shadow-xl hover:border-primary/30 transition-all duration-300"
                    data-speaker-card
                    data-event-id="{{ e($speaker['_filter_event_id'] ?? '') }}"
                    data-theme="{{ e($speaker['_filter_theme_label'] ?? '') }}"
                    data-topic="{{ e(trim((string) ($speaker['_filter_topic_title'] ?? ''))) }}"
                    data-search="{{ e($speaker['_filter_search_normalized'] ?? '') }}"
                >
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
                        @if(!empty($speaker['_filter_event_title']))
                            <p class="text-center text-xs text-muted-foreground leading-snug">
                                {{ $speaker['_filter_event_title'] }}
                                @if(!empty($speaker['_filter_event_year']))
                                    <span class="text-muted-foreground/80">· {{ $speaker['_filter_event_year'] }}</span>
                                @endif
                            </p>
                        @endif
                        <h2 class="font-serif text-xl font-bold text-foreground leading-snug text-center sm:text-left">{{ $speaker['name'] ?? 'Speaker' }}</h2>
                        <p class="text-muted-foreground text-sm mt-1 text-center sm:text-left">{{ $speaker['title'] ?? '' }}{{ !empty($speaker['organization']) ? ' · ' . $speaker['organization'] : '' }}</p>
                        @php
                            $cardThemeLabel = trim((string) ($speaker['_filter_theme_label'] ?? ''));
                            $cardTopicTitle = trim((string) ($speaker['_filter_topic_title'] ?? ''));
                            $sameTopicAsTheme = $cardTopicTitle !== '' && $cardThemeLabel !== '' && mb_strtolower($cardTopicTitle) === mb_strtolower($cardThemeLabel);
                        @endphp
                        @if($cardThemeLabel !== '' || $cardTopicTitle !== '')
                            <p class="text-xs text-muted-foreground text-center sm:text-left leading-relaxed">
                                @if($cardThemeLabel !== '')
                                    <span class="font-medium text-foreground/80">{{ $cardThemeLabel }}</span>
                                @endif
                                @if($cardTopicTitle !== '' && ! $sameTopicAsTheme)
                                    @if($cardThemeLabel !== '')
                                        <span class="text-muted-foreground"> · </span>
                                    @endif
                                    <span>{{ $cardTopicTitle }}</span>
                                @endif
                            </p>
                        @endif
                        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                            @if(($speaker['is_key_speaker'] ?? $speaker['key_speaker'] ?? false))
                                <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded">Key Speaker</span>
                            @endif
                            @if(($speaker['is_session_leader'] ?? $speaker['session_leader'] ?? false))
                                <span class="px-2 py-0.5 bg-muted text-foreground text-xs font-medium rounded">Session Leader</span>
                            @endif
                        </div>
                        @php
                            $sidAll = isset($speaker['id']) ? (string) $speaker['id'] : '';
                        @endphp
                        @if($sidAll !== '')
                            <a href="{{ route('frontend.speaker.show', $sidAll) }}" class="inline-flex mt-2 items-center justify-center rounded-xl border border-primary/40 bg-primary/5 px-4 py-2.5 text-sm font-semibold text-primary hover:bg-primary/10 hover:border-primary/60 transition-colors w-full sm:w-auto">View details</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    <script>
        (function () {
            const eventsMeta = @json($eventsForSpeakerFilters);
            const grid = document.querySelector('[data-speakers-grid]');
            const cards = grid ? [...grid.querySelectorAll('[data-speaker-card]')] : [];
            const eventSelect = document.querySelector('[data-speakers-filter-event]');
            const themeSelect = document.querySelector('[data-speakers-filter-theme]');
            const searchInput = document.querySelector('[data-speakers-search]');
            const emptyHint = document.getElementById('speakers-empty-hint');
            if (!grid || !eventSelect || !themeSelect || !searchInput) {
                return;
            }

            if (cards.length === 0) {
                grid.classList.add('hidden');
                if (emptyHint) {
                    emptyHint.textContent = 'No speakers are available yet.';
                    emptyHint.classList.remove('hidden');
                    emptyHint.removeAttribute('hidden');
                }
                return;
            }

            const FILTER_EMPTY_MESSAGE = 'No speakers match the current filters. Try broadening event, theme, or search.';

            const normalizeQuery = function (raw) {
                return String(raw || '')
                    .trim()
                    .toLowerCase()
                    .replace(/\s+/g, ' ');
            };

            const refillThemesForEvent = function (eventId) {
                themeSelect.innerHTML = '';
                const fallback = document.createElement('option');
                fallback.value = '';
                fallback.textContent = '';
                themeSelect.appendChild(fallback);

                if (!eventId) {
                    themeSelect.disabled = true;
                    themeSelect.setAttribute('aria-disabled', 'true');
                    fallback.textContent = 'All themes — select an event first';
                    themeSelect.selectedIndex = 0;
                    return;
                }

                let row = null;
                if (Array.isArray(eventsMeta)) {
                    eventsMeta.some(function (e) {
                        if (e && String(e.id) === String(eventId)) {
                            row = e;
                            return true;
                        }
                        return false;
                    });
                }
                const themes = row && Array.isArray(row.themes) ? row.themes : [];

                fallback.textContent = themes.length === 0 ? 'No themes for this event' : 'All themes';
                if (themes.length === 0) {
                    themeSelect.disabled = true;
                    themeSelect.setAttribute('aria-disabled', 'true');
                    themeSelect.selectedIndex = 0;
                    return;
                }

                themeSelect.disabled = false;
                themeSelect.removeAttribute('aria-disabled');

                themes.forEach(function (label) {
                    if (!label || String(label).trim() === '') {
                        return;
                    }
                    const opt = document.createElement('option');
                    opt.value = String(label).trim();
                    opt.textContent = String(label).trim();
                    themeSelect.appendChild(opt);
                });
                themeSelect.selectedIndex = 0;
            };

            const applyFilters = function () {
                const eventVal = eventSelect.value;
                const themeVal = normalizeQuery(themeSelect.value) === normalizeQuery('')
                    ? ''
                    : String(themeSelect.value).trim();
                const needle = normalizeQuery(searchInput.value);

                let visible = 0;

                cards.forEach(function (card) {
                    const ev = card.dataset.eventId || '';
                    const hay = normalizeQuery(card.dataset.search || '');
                    let show = true;
                    if (eventVal && String(ev) !== String(eventVal)) {
                        show = false;
                    }
                    if (show && themeVal) {
                        const th = (card.dataset.theme || '').trim();
                        const tp = (card.dataset.topic || '').trim();
                        if (th !== themeVal && tp !== themeVal) {
                            show = false;
                        }
                    }
                    if (show && needle !== '') {
                        if (!hay.includes(needle)) {
                            show = false;
                        }
                    }
                    card.classList.toggle('hidden', !show);
                    card.toggleAttribute('hidden', !show);
                    if (show) {
                        visible += 1;
                    }
                });

                const filteredToNone = visible === 0 && cards.length > 0;
                grid.classList.toggle('hidden', filteredToNone);
                if (emptyHint) {
                    if (filteredToNone) {
                        emptyHint.textContent = FILTER_EMPTY_MESSAGE;
                    }
                    emptyHint.classList.toggle('hidden', !filteredToNone);
                    emptyHint.toggleAttribute('hidden', !filteredToNone);
                }
            };

            refillThemesForEvent(eventSelect.value);
            eventSelect.addEventListener('change', function () {
                refillThemesForEvent(eventSelect.value);
                applyFilters();
            });
            themeSelect.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);

            applyFilters();
        })();
    </script>
@endsection
