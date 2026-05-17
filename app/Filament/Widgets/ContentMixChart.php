<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasReadableChartOptions;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\ChartWidget;

class ContentMixChart extends ChartWidget
{
    use HasReadableChartOptions;

    protected static ?int $sort = 4;

    protected static ?string $heading = 'Content mix';

    protected static ?string $description = 'Proportion of each content type across the archive';

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
        $chart = app(DashboardAnalyticsService::class)->contentMix();

        $labels = [];
        $data = [];

        foreach ($chart['labels'] as $index => $label) {
            $value = (int) ($chart['data'][$index] ?? 0);
            if ($value <= 0) {
                continue;
            }
            $labels[] = $label;
            $data[] = $value;
        }

        if ($labels === []) {
            $labels = ['No content yet'];
            $data = [1];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $this->colorsForCount(count($data)),
                    'borderWidth' => 2,
                    'borderColor' => 'rgba(255, 255, 255, 0.08)',
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return $this->doughnutChartOptions();
    }
}
