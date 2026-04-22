<?php

return array_values(array_filter([
    App\Providers\AppServiceProvider::class,
    class_exists(\Filament\PanelProvider::class) ? App\Providers\Filament\AdminPanelProvider::class : null,
]));
