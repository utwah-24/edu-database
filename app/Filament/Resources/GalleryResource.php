<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('url')
                ->required()
                ->url()
                ->maxLength(2048),
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
                    ->label('Event Year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->limit(60),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\FileUpload::make('images')
                            ->disk('public')
                            ->directory('gallery')
                            ->image()
                            ->multiple()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                                if (blank($safeBase)) {
                                    $safeBase = 'image';
                                }

                                return $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                            })
                            ->required(),
                        Forms\Components\TextInput::make('start_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->using(function (array $data): Gallery {
                        $paths = $data['images'] ?? [];
                        $eventId = $data['event_id'];
                        $startOrder = (int) ($data['start_order'] ?? 0);

                        $first = null;
                        foreach (array_values($paths) as $index => $path) {
                            $model = Gallery::create([
                                'event_id' => $eventId,
                                'url' => Storage::disk('public')->url($path),
                                'order' => $startOrder + $index,
                            ]);
                            $first ??= $model;
                        }

                        return $first;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGalleries::route('/'),
        ];
    }
}
