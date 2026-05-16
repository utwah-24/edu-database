<?php

namespace App\Filament\Widgets;

use App\Services\DashboardAnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentInventoryStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Content library';

    protected ?string $description = 'All downloadable and editorial assets in the system';

    protected function getStats(): array
    {
        $inventory = app(DashboardAnalyticsService::class)->contentInventory();
        $total = array_sum($inventory);

        return [
            Stat::make('Topic tracks', (string) $inventory['topics'])
                ->description('Conference themes / tracks')
                ->color('primary'),

            Stat::make('Programme items', (string) $inventory['programmes'])
                ->description('Scheduled sessions')
                ->color('success'),

            Stat::make('Documents', (string) $inventory['resources'])
                ->description('Files & downloads')
                ->color('warning'),

            Stat::make('Gallery & media', (string) ($inventory['galleries'] + $inventory['media']))
                ->description("{$inventory['galleries']} photos · {$inventory['media']} media")
                ->color('info'),

            Stat::make('FAQs', (string) $inventory['faqs'])
                ->description("{$inventory['summaries']} summaries · {$inventory['event_themes']} theme areas")
                ->color('gray'),

            Stat::make('Total content', (string) $total)
                ->description('All content records combined')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
        ];
    }
}
