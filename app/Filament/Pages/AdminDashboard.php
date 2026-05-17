<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardWelcomeWidget;
use App\Filament\Widgets\ContentMixChart;
use App\Filament\Widgets\EventsBreakdownTable;
use App\Filament\Widgets\EventsByYearChart;
use App\Filament\Widgets\SponsorTiersChart;
use App\Filament\Widgets\SystemOverviewStats;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class AdminDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getHeading(): string | Htmlable
    {
        return '';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            DashboardWelcomeWidget::class,
            SystemOverviewStats::class,
            EventsByYearChart::class,
            ContentMixChart::class,
            SponsorTiersChart::class,
            EventsBreakdownTable::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'lg' => 3,
        ];
    }
}
