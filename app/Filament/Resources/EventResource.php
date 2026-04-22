<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('year')
                ->numeric()
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('cover_image')
                ->disk('public')
                ->directory('events')
                ->image()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    if (blank($safeBase)) {
                        $safeBase = 'cover-image';
                    }

                    return $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                }),
            Forms\Components\TextInput::make('location')
                ->maxLength(255),
            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('end_date')
                ->afterOrEqual('start_date'),
            Forms\Components\Toggle::make('is_published')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEvents::route('/'),
        ];
    }
}
