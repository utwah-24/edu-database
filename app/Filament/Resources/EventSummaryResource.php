<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventSummaryResource\Pages;
use App\Models\EventSummary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventSummaryResource extends Resource
{
    protected static ?string $model = EventSummary::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')->relationship('event', 'title')->required()->searchable()->preload(),
            Forms\Components\RichEditor::make('summary')->required()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('event.year')->label('Year')->sortable(),
            Tables\Columns\TextColumn::make('summary')->html()->limit(80),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->since(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->headerActions([
            Tables\Actions\CreateAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEventSummaries::route('/'),
        ];
    }
}
