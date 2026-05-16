<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdminDashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
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
            ->brandName('Utafiti Elimu Admin')
            ->colors([
                'primary' => Color::hex('#E86B4A'),
                'gray' => Color::Slate,
            ])
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
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
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => view('filament.hooks.admin-theme')->render(),
            )
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
