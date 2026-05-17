<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasReadableChartOptions;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\ChartWidget;

class SponsorTiersChart extends ChartWidget
{
    use HasReadableChartOptions;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Sponsor tiers';

    protected static ?string $description = 'Partners grouped by sponsorship level';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $chart = app(DashboardAnalyticsService::class)->sponsorTiers();

        $count = count($chart['data']);

        return [
            'datasets' => [
                [
                    'data' => $chart['data'],
                    'backgroundColor' => $this->colorsForCount($count),
                    'borderWidth' => 2,
                    'borderColor' => 'rgba(255, 255, 255, 0.08)',
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getOptions(): array
    {
        return $this->doughnutChartOptions();
    }
}
