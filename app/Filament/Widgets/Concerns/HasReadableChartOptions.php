<?php

namespace App\Filament\Widgets\Concerns;

trait HasReadableChartOptions
{
    /**
     * Brand-aligned palette for dashboard charts.
     *
     * @return list<string>
     */
    protected function chartPalette(): array
    {
        return [
            'rgba(232, 107, 74, 0.92)',
            'rgba(23, 108, 171, 0.88)',
            'rgba(30, 42, 58, 0.88)',
            'rgba(255, 159, 64, 0.88)',
            'rgba(75, 192, 192, 0.88)',
            'rgba(153, 102, 255, 0.88)',
            'rgba(255, 99, 132, 0.88)',
            'rgba(148, 163, 184, 0.85)',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function chartTooltip(): array
    {
        return [
            'backgroundColor' => 'rgba(30, 42, 58, 0.96)',
            'titleColor' => '#f9fafb',
            'bodyColor' => '#e5e7eb',
            'borderColor' => 'rgba(232, 107, 74, 0.45)',
            'borderWidth' => 1,
            'padding' => 12,
            'cornerRadius' => 8,
            'displayColors' => true,
            'boxPadding' => 6,
        ];
    }

    /**
     * Chart.js options that stay legible in Filament light and dark mode.
     *
     * @return array<string, mixed>
     */
    protected function readableChartOptions(bool $includeScales = true, string $legendPosition = 'top'): array
    {
        $options = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 650,
                'easing' => 'easeOutQuart',
            ],
            'plugins' => [
                'legend' => [
                    'position' => $legendPosition,
                    'align' => 'center',
                    'labels' => [
                        'color' => '#9ca3af',
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 18,
                        'font' => [
                            'size' => 12,
                            'weight' => '500',
                        ],
                    ],
                ],
                'tooltip' => $this->chartTooltip(),
            ],
        ];

        if ($includeScales) {
            $options['scales'] = [
                'x' => [
                    'ticks' => [
                        'color' => '#9ca3af',
                        'font' => ['size' => 11, 'weight' => '500'],
                    ],
                    'grid' => ['display' => false],
                    'border' => ['display' => false],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => '#9ca3af',
                        'precision' => 0,
                        'font' => ['size' => 11],
                        'padding' => 8,
                    ],
                    'grid' => [
                        'color' => 'rgba(156, 163, 175, 0.15)',
                        'drawBorder' => false,
                    ],
                    'border' => ['display' => false],
                ],
            ];
        }

        return $options;
    }

    /**
     * @return array<string, mixed>
     */
    protected function doughnutChartOptions(): array
    {
        return array_merge($this->readableChartOptions(includeScales: false, legendPosition: 'bottom'), [
            'cutout' => '68%',
            'plugins' => array_merge($this->readableChartOptions(includeScales: false)['plugins'], [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#9ca3af',
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 14,
                        'font' => ['size' => 11, 'weight' => '500'],
                    ],
                ],
            ]),
        ]);
    }

    /**
     * @param  list<int|float>  $values
     * @return list<string>
     */
    protected function colorsForCount(int $count): array
    {
        $palette = $this->chartPalette();

        return array_slice(
            array_merge($palette, $palette),
            0,
            max($count, 1),
        );
    }
}
