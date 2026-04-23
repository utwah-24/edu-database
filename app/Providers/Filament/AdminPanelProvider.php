<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->resources([
                \App\Filament\Resources\EventResource::class,
                \App\Filament\Resources\TopicResource::class,
                \App\Filament\Resources\GalleryResource::class,
                \App\Filament\Resources\SpeakerResource::class,
                \App\Filament\Resources\SponsorResource::class,
                \App\Filament\Resources\EventThemeResource::class,
                \App\Filament\Resources\EventSummaryResource::class,
                \App\Filament\Resources\EventProgrammeResource::class,
                \App\Filament\Resources\MediaResource::class,
                \App\Filament\Resources\EventResourceFileResource::class,
                \App\Filament\Resources\FAQResource::class,
                \App\Filament\Resources\AttendanceResource::class,
                \App\Filament\Resources\UserResource::class,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
