<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?string $navigationLabel = 'Товары';
    protected static ?string $modelLabel = 'Товар';
    protected static ?string $pluralModelLabel = 'Товары';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Имя')
                        ->required(),
                    Forms\Components\RichEditor::make('description')
                        ->label('Описание')
                        ->required(),
                    Forms\Components\TextInput::make('sku')
                        ->label('Артикул'),
                    Forms\Components\TextInput::make('price')
                        ->label('Цена')
                        ->numeric()
                        ->required(),
                    Forms\Components\Checkbox::make('has_old_price')
                        ->label('Указать старую цену')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('old_price', null)),
                    Forms\Components\TextInput::make('old_price')
                        ->label('Старая цена')
                        ->numeric()
                        ->visible(fn (callable $get) => $get('has_old_price')),
                    Forms\Components\KeyValue::make('characteristics')
                        ->label('Характеристики'),
                    Forms\Components\Select::make('brand_id')
                        ->label('Бренд')
                        ->relationship('brand', 'name')
                        ->required(),
                    Forms\Components\Select::make('category_id')
                        ->label('Категория')
                        ->relationship('category', 'name')
                        ->required(),
                    Forms\Components\FileUpload::make('images')
                        ->label('Фотографии')
                        ->multiple()
                        ->maxFiles(5)
                        ->image()
                        ->directory('products'),
                    Forms\Components\Toggle::make('is_hit')
                        ->label('Хит продаж'),
                    Forms\Components\Select::make('availability')
                        ->label('Есть в наличии')
                        ->options([
                            'да' => 'Да',
                            'уточните у нас' => 'Уточните у нас',
                        ])
                        ->default('да'),
                    Forms\Components\Select::make('status')
                        ->label('Статус')
                        ->options([
                            'active' => 'Показать',
                            'inactive' => 'Скрыть',
                        ])
                        ->default('active'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Имя')->searchable(),
            Tables\Columns\TextColumn::make('price')->label('Цена')->sortable(),
            Tables\Columns\TextColumn::make('old_price')->label('Старая цена')->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('brand.name')->label('Бренд')->sortable(),
            Tables\Columns\TextColumn::make('category.name')->label('Категория')->sortable(),
            Tables\Columns\IconColumn::make('is_hit')->label('Хит продаж')->boolean(),
            Tables\Columns\TextColumn::make('availability')->label('Есть в наличии'),
            Tables\Columns\TextColumn::make('status')->label('Статус'),
        ])->actions([
            Tables\Actions\EditAction::make()->label('Редактировать'),
            Tables\Actions\DeleteAction::make()->label('Удалить'),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label('Удалить выбранные'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
