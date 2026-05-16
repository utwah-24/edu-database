<?php

namespace App\Filament\Widgets;

use App\Services\DashboardAnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverviewStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Conference platform';

    protected ?string $description = 'Live totals across events, people, and registrations';

    protected function getStats(): array
    {
        $stats = app(DashboardAnalyticsService::class)->overview();

        return [
            Stat::make('Events', (string) $stats['events_total'])
                ->description("{$stats['events_published']} published · {$stats['events_draft']} draft")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->chart([$stats['events_draft'], $stats['events_published']]),

            Stat::make('Speakers', (string) $stats['speakers_total'])
                ->description("{$stats['speakers_key']} key · {$stats['speakers_leaders']} session leaders")
                ->descriptionIcon('heroicon-m-microphone')
                ->color('success'),

            Stat::make('Sponsors', (string) $stats['sponsors_total'])
                ->description('Partner organisations')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('warning'),

            Stat::make('Registrations', (string) $stats['attendances_total'])
                ->description("{$stats['check_in_rate']}% checked in ({$stats['attendances_checked_in']})")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
