<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResourceFileResource\Pages;
use App\Models\EventResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResourceFileResource extends Resource
{
    protected static ?string $model = EventResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';
    protected static ?string $navigationLabel = 'Event Documents';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')->relationship('event', 'title')->required()->searchable()->preload(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\RichEditor::make('description')->columnSpanFull(),
            Forms\Components\FileUpload::make('file_path')
                ->directory('event-documents')
                ->preserveFilenames()
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'text/plain',
                ])
                ->label('Document file'),
            Forms\Components\TextInput::make('file_type')->maxLength(255),
            Forms\Components\TextInput::make('url')->url()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('event.year')->label('Year')->sortable(),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('file_type'),
            Tables\Columns\TextColumn::make('file_path')->limit(40),
            Tables\Columns\TextColumn::make('url')->limit(40),
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
            'index' => Pages\ManageEventResourceFiles::route('/'),
        ];
    }
}
