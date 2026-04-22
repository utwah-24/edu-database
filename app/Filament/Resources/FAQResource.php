<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FAQResource\Pages;
use App\Models\FAQ;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FAQResource extends Resource
{
    protected static ?string $model = FAQ::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\RichEditor::make('question')
                ->required()
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('answer')
                ->required()
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
                Tables\Columns\TextColumn::make('question')
                    ->html()
                    ->limit(80),
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
            'index' => Pages\ManageFAQs::route('/'),
        ];
    }
}
