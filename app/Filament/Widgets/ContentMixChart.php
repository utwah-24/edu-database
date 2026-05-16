<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasReadableChartOptions;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\ChartWidget;

class ContentMixChart extends ChartWidget
{
    use HasReadableChartOptions;
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Content distribution';

    protected static ?string $description = 'Share of each content type in the database';

    protected static ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $chart = app(DashboardAnalyticsService::class)->contentMix();

        return [
            'datasets' => [
                [
                    'data' => $chart['data'],
                    'backgroundColor' => [
                        'rgba(232, 107, 74, 0.9)',
                        'rgba(30, 42, 58, 0.85)',
                        'rgba(23, 108, 171, 0.85)',
                        'rgba(255, 159, 64, 0.85)',
                        'rgba(75, 192, 192, 0.85)',
                        'rgba(153, 102, 255, 0.85)',
                        'rgba(255, 99, 132, 0.85)',
                        'rgba(201, 203, 207, 0.85)',
                    ],
                    'borderWidth' => 0,
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
