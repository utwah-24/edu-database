<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContentInventoryStats;
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
        $name = auth()->user()?->name ?? 'Admin';

        return "Hey, {$name}";
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Overview of events, speakers, sponsors, and all conference content in the archive.';
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            SystemOverviewStats::class,
            ContentInventoryStats::class,
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
            'md' => 2,
            'xl' => 3,
        ];
    }
}
