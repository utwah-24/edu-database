<?php

namespace App\Filament\Widgets\Concerns;

trait HasReadableChartOptions
{
    /**
     * Chart.js options that stay legible in Filament light and dark mode.
     *
     * @return array<string, mixed>
     */
    protected function readableChartOptions(bool $includeScales = true): array
    {
        $options = [
            'plugins' => [
                'legend' => [
                    'labels' => [
                        'color' => '#9ca3af',
                        'usePointStyle' => true,
                        'padding' => 16,
                    ],
                ],
            ],
        ];

        if ($includeScales) {
            $options['scales'] = [
                'x' => [
                    'ticks' => ['color' => '#9ca3af'],
                    'grid' => ['color' => 'rgba(156, 163, 175, 0.2)', 'drawBorder' => false],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['color' => '#9ca3af', 'precision' => 0],
                    'grid' => ['color' => 'rgba(156, 163, 175, 0.2)', 'drawBorder' => false],
                ],
            ];
        }

        return $options;
    }
}
