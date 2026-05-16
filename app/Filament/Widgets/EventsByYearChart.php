<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasReadableChartOptions;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\ChartWidget;

class EventsByYearChart extends ChartWidget
{
    use HasReadableChartOptions;
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Events by year';

    protected static ?string $description = 'Conference editions in the archive';

    protected static ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
    ];

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $chart = app(DashboardAnalyticsService::class)->eventsByYear();

        if ($chart['labels'] === []) {
            $chart = ['labels' => ['No data'], 'data' => [0]];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => $chart['data'],
                    'backgroundColor' => 'rgba(232, 107, 74, 0.85)',
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getOptions(): array
    {
        return $this->readableChartOptions();
    }
}
