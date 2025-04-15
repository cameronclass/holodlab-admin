<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Наши работы';
    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('description')
                    ->label('Описание')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('Фотографии')
                    ->multiple()
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->directory('projects')
                    ->columnSpanFull(),
                Select::make('tags')
                    ->label('Теги')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                ImageColumn::make('images.0')
                    ->label('Фотографии')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->getStateUsing(function ($record) {
                        // Если images - массив, возвращаем массив ссылок
                        if (is_array($record->images)) {
                            return $record->images;
                        }
                        if (is_string($record->images) && !empty($record->images)) {
                            return [$record->images];
                        }
                        return [];
                    }),
                TextColumn::make('tags.name')
                    ->label('Теги')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
