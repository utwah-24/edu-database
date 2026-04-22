<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventProgrammeResource\Pages;
use App\Models\EventProgramme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventProgrammeResource extends Resource
{
    protected static ?string $model = EventProgramme::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')->relationship('event', 'title')->required()->searchable()->preload(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\RichEditor::make('description')->columnSpanFull(),
            Forms\Components\DateTimePicker::make('start_time'),
            Forms\Components\DateTimePicker::make('end_time')->afterOrEqual('start_time'),
            Forms\Components\TextInput::make('location')->maxLength(255),
            Forms\Components\TextInput::make('speaker')->maxLength(255),
            Forms\Components\TextInput::make('event_pdf')
                ->label('Event PDF (Google Drive)')
                ->url()
                ->maxLength(2048)
                ->placeholder('https://drive.google.com/...')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('order')->numeric()->default(0)->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('event.year')->label('Year')->sortable(),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('start_time')->dateTime(),
            Tables\Columns\TextColumn::make('location')->limit(30),
            Tables\Columns\TextColumn::make('event_pdf')
                ->label('PDF')
                ->limit(40)
                ->url(fn (?string $state): ?string => filled($state) ? $state : null)
                ->openUrlInNewTab()
                ->toggleable(),
            Tables\Columns\TextColumn::make('order')->sortable(),
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
            'index' => Pages\ManageEventProgrammes::route('/'),
        ];
    }
}
