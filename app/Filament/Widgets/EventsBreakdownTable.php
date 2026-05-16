<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EventsBreakdownTable extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Event content breakdown';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->withCount([
                        'speakers',
                        'topics',
                        'sponsors',
                        'programmes',
                        'resources',
                        'galleries',
                        'attendances',
                        'faqs',
                        'media',
                    ])
                    ->orderByDesc('year')
            )
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Year')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Event')
                    ->searchable()
                    ->limit(40)
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Live')
                    ->boolean(),

                Tables\Columns\TextColumn::make('speakers_count')
                    ->label('Speakers')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('topics_count')
                    ->label('Tracks')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sponsors_count')
                    ->label('Sponsors')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('programmes_count')
                    ->label('Programme')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('resources_count')
                    ->label('Docs')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('galleries_count')
                    ->label('Gallery')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('attendances_count')
                    ->label('Registrations')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('content_score')
                    ->label('Completeness')
                    ->state(function (Event $record): string {
                        $score = $record->speakers_count
                            + $record->topics_count
                            + $record->sponsors_count
                            + $record->programmes_count
                            + $record->resources_count
                            + $record->galleries_count;

                        return $score >= 12 ? 'Strong' : ($score >= 6 ? 'Good' : 'Needs content');
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Strong' => 'success',
                        'Good' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25]);
    }
}
