<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?string $navigationLabel = 'Theme';

    protected static ?string $modelLabel = 'Theme';

    protected static ?string $pluralModelLabel = 'Themes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('topic_date')
                ->required(),
            Forms\Components\RichEditor::make('content')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('topic_picture')
                ->maxLength(255),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('topic_date')
                    ->label('Theme date')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ManageTopics::route('/'),
        ];
    }
}
