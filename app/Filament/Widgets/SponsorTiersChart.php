<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasReadableChartOptions;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\ChartWidget;

class SponsorTiersChart extends ChartWidget
{
    use HasReadableChartOptions;
    protected static ?int $sort = 6;

    protected static ?string $heading = 'Sponsors by tier';

    protected static ?string $maxHeight = '240px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
    ];

    protected static ?string $description = 'Partner organisations grouped by sponsorship level';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $chart = app(DashboardAnalyticsService::class)->sponsorTiers();

        return [
            'datasets' => [
                [
                    'data' => $chart['data'],
                    'backgroundColor' => [
                        'rgba(232, 107, 74, 0.9)',
                        'rgba(23, 108, 171, 0.85)',
                        'rgba(30, 42, 58, 0.85)',
                        'rgba(255, 159, 64, 0.85)',
                        'rgba(75, 192, 192, 0.85)',
                    ],
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getOptions(): array
    {
        return $this->readableChartOptions(includeScales: false);
    }
}
