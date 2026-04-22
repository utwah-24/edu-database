<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventThemeResource\Pages;
use App\Models\EventTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventThemeResource extends Resource
{
    protected static ?string $model = EventTheme::class;
    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')->relationship('event', 'title')->required()->searchable()->preload(),
            Forms\Components\TextInput::make('theme')->required()->maxLength(255),
            Forms\Components\RichEditor::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('theme')->searchable(),
            Tables\Columns\TextColumn::make('event.year')->label('Year')->sortable(),
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
            'index' => Pages\ManageEventThemes::route('/'),
        ];
    }
}
