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

    protected static ?string $description = 'Conference editions published in the archive';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
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
                    'label' => 'Conference editions',
                    'data' => $chart['data'],
                    'backgroundColor' => 'rgba(232, 107, 74, 0.88)',
                    'hoverBackgroundColor' => 'rgba(232, 107, 74, 1)',
                    'borderRadius' => 10,
                    'borderSkipped' => false,
                    'maxBarThickness' => 48,
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getOptions(): array
    {
        $options = $this->readableChartOptions();

        $options['plugins']['legend']['display'] = false;

        return $options;
    }
}
