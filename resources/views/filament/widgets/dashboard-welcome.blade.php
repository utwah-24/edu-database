@php
    $stats = $stats ?? [];
    $quickLinks = $quickLinks ?? [];
    $firstName = trim(explode(' ', (string) ($userName ?? 'Admin'))[0] ?: 'Admin');
@endphp

<x-filament-widgets::widget class="admin-welcome-widget">
    <div class="admin-welcome-banner">
        <div class="admin-welcome-banner__glow" aria-hidden="true"></div>
        <div class="admin-welcome-banner__pattern" aria-hidden="true"></div>

        <div class="admin-welcome-banner__inner">
            <div class="admin-welcome-banner__main">
                <p class="admin-welcome-banner__eyebrow">
                    <x-filament::icon icon="heroicon-m-sparkles" class="h-4 w-4" />
                    Utafiti Elimu Admin
                </p>
                <h2 class="admin-welcome-banner__title">
                    Welcome back, {{ $firstName }}
                </h2>
                <p class="admin-welcome-banner__lead">
                    Your conference archive at a glance — events, speakers, sponsors, and published content in one place.
                </p>

                <div class="admin-welcome-banner__actions">
                    @foreach($quickLinks as $link)
                        <a href="{{ $link['url'] }}" class="admin-welcome-banner__action">
                            <x-filament::icon :icon="$link['icon']" class="h-4 w-4 shrink-0" />
                            <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="admin-welcome-banner__metrics" aria-label="Platform summary">
                <div class="admin-welcome-banner__metric">
                    <span class="admin-welcome-banner__metric-value">{{ number_format($stats['events_total'] ?? 0) }}</span>
                    <span class="admin-welcome-banner__metric-label">Events</span>
                    <span class="admin-welcome-banner__metric-hint">{{ $stats['events_published'] ?? 0 }} published</span>
                </div>
                <div class="admin-welcome-banner__metric">
                    <span class="admin-welcome-banner__metric-value">{{ number_format($stats['speakers_total'] ?? 0) }}</span>
                    <span class="admin-welcome-banner__metric-label">Speakers</span>
                    <span class="admin-welcome-banner__metric-hint">{{ $stats['speakers_key'] ?? 0 }} key speakers</span>
                </div>
                <div class="admin-welcome-banner__metric">
                    <span class="admin-welcome-banner__metric-value">{{ number_format($stats['sponsors_total'] ?? 0) }}</span>
                    <span class="admin-welcome-banner__metric-label">Sponsors</span>
                    <span class="admin-welcome-banner__metric-hint">Partner organisations</span>
                </div>
                <div class="admin-welcome-banner__metric admin-welcome-banner__metric--accent">
                    <span class="admin-welcome-banner__metric-value">{{ number_format($contentTotal ?? 0) }}</span>
                    <span class="admin-welcome-banner__metric-label">Content items</span>
                    <span class="admin-welcome-banner__metric-hint">{{ $stats['check_in_rate'] ?? 0 }}% check-in rate</span>
                </div>
            </div>
        </div>

        <div class="admin-welcome-banner__footer">
            <span class="admin-welcome-banner__footer-item">
                <x-filament::icon icon="heroicon-m-clock" class="h-4 w-4" />
                {{ now()->format('l, j F Y') }}
            </span>
            <span class="admin-welcome-banner__footer-dot" aria-hidden="true"></span>
            <span class="admin-welcome-banner__footer-item">
                <x-filament::icon icon="heroicon-m-user-group" class="h-4 w-4" />
                {{ number_format($stats['attendances_total'] ?? 0) }} registrations
            </span>
            <span class="admin-welcome-banner__footer-dot" aria-hidden="true"></span>
            <span class="admin-welcome-banner__footer-item">
                <x-filament::icon icon="heroicon-m-chart-bar" class="h-4 w-4" />
                {{ $stats['publish_rate'] ?? 0 }}% events published
            </span>
        </div>
    </div>
</x-filament-widgets::widget>
