<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\GalleryResource;
use App\Filament\Resources\SpeakerResource;
use App\Filament\Resources\SponsorResource;
use App\Services\DashboardAnalyticsService;
use Filament\Widgets\Widget;

class DashboardWelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-welcome';

    protected static ?int $sort = -10;

    protected int | string | array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $stats = app(DashboardAnalyticsService::class)->overview();
        $inventory = app(DashboardAnalyticsService::class)->contentInventory();

        return [
            'userName' => auth()->user()?->name ?? 'Admin',
            'stats' => $stats,
            'contentTotal' => array_sum($inventory),
            'quickLinks' => [
                [
                    'label' => 'Manage events',
                    'url' => EventResource::getUrl('index'),
                    'icon' => 'heroicon-o-calendar-days',
                ],
                [
                    'label' => 'Speakers',
                    'url' => SpeakerResource::getUrl('index'),
                    'icon' => 'heroicon-o-microphone',
                ],
                [
                    'label' => 'Sponsors',
                    'url' => SponsorResource::getUrl('index'),
                    'icon' => 'heroicon-o-building-office-2',
                ],
                [
                    'label' => 'Gallery',
                    'url' => GalleryResource::getUrl('index'),
                    'icon' => 'heroicon-o-photo',
                ],
            ],
        ];
    }
}
