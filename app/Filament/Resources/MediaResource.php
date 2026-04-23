<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('title')
                ->maxLength(255),
            Forms\Components\Select::make('type')
                ->options([
                    'image' => 'Image',
                    'video' => 'Video',
                    'document' => 'Document',
                ])
                ->required(),
            Forms\Components\TextInput::make('file_path')
                ->label('Media URL')
                ->url()
                ->required()
                ->maxLength(2048),
            Forms\Components\FileUpload::make('thumbnail')
                ->disk('public')
                ->directory('media/thumbnails')
                ->image()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    if (blank($safeBase)) {
                        $safeBase = 'thumbnail';
                    }

                    return $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                }),
            Forms\Components\RichEditor::make('description')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.year')
                    ->label('Year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('file_path')
                    ->limit(40),
                Tables\Columns\TextColumn::make('order')
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
            'index' => Pages\ManageMedia::route('/'),
        ];
    }
}
