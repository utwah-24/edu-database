<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Models\Speaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;
    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')->relationship('event', 'title')->required()->searchable()->preload(),
            Forms\Components\Select::make('topic_id')->label('Theme')->relationship('topic', 'title')->searchable()->preload(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('title')->maxLength(255),
            Forms\Components\TextInput::make('organization')->maxLength(255),
            Forms\Components\RichEditor::make('bio')->columnSpanFull(),
            Forms\Components\FileUpload::make('photo')
                ->disk('public')
                ->directory('speakers')
                ->image()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    if (blank($safeBase)) {
                        $safeBase = 'image';
                    }

                    return $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                }),
            Forms\Components\TextInput::make('email')->email()->maxLength(255),
            Forms\Components\TextInput::make('linkedin')->maxLength(255),
            Forms\Components\TextInput::make('twitter')->maxLength(255),
            Forms\Components\TextInput::make('order')->numeric()->default(0)->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('event.year')->label('Year')->sortable(),
            Tables\Columns\TextColumn::make('topic.title')->label('Theme')->limit(30),
            Tables\Columns\TextColumn::make('organization')->limit(30),
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
            'index' => Pages\ManageSpeakers::route('/'),
        ];
    }
}
