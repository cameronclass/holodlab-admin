# План реализации страницы Брендов в админ-панели

## 1. Подготовка базы данных

### Миграция для таблицы brands

```php
// Поля таблицы:
- id (primary key)
- name (string, обязательное)
- slug (string, уникальный, для URL)
- image_path (string, nullable, для хранения пути к изображению)
- created_at, updated_at (timestamps)
```

## 2. Создание модели Brand

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (!$brand->slug) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && !$brand->isDirty('slug')) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
}
```

## 3. Создание Filament ресурса для Брендов

```php
namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?string $navigationLabel = 'Бренды';
    protected static ?string $modelLabel = 'Бренд';
    protected static ?string $pluralModelLabel = 'Бренды';
    protected static ?int $navigationSort = 2; // После категорий

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->minLength(2)
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Название')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->label('URL')
                            ->helperText('Генерируется автоматически из названия')
                            ->rules(['alpha_dash']),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Изображение')
                            ->image()
                            ->disk('brands')
                            ->directory('logos')
                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg'])
                            ->imagePreviewHeight('100')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->visibility('public')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Логотип')
                    ->disk('brands')
                    ->square()
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
```

## 4. API для Брендов

### Контроллер BrandController

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Cache::remember('api_brands', 3600, function () {
            return Brand::select(['id', 'name', 'slug', 'image_path'])
                ->get()
                ->map(function ($brand) {
                    if ($brand->image_path) {
                        $brand->image_path = asset('storage/brands/' . $brand->image_path);
                    }
                    return $brand;
                });
        });

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }
}
```

### Добавление маршрута в API

```php
// В файле routes/api.php
Route::middleware('api.token')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/brands', [BrandController::class, 'index']);
});
```

## 5. Команды для выполнения

1. Создание миграции:

```bash
php artisan make:migration create_brands_table
```

2. Создание модели:

```bash
php artisan make:model Brand
```

3. Создание Filament ресурса:

```bash
php artisan make:filament-resource Brand
```

4. Настройка хранилища для изображений брендов:

```php
// В config/filesystems.php добавить диск
'brands' => [
    'driver' => 'local',
    'root' => storage_path('app/public/brands'),
    'url' => env('APP_URL').'/storage/brands',
    'visibility' => 'public',
],
```

## 6. Валидация

-   Имя бренда: обязательное, минимум 2 символа
-   URL (slug): только английские буквы, цифры и дефисы
-   Изображение: обязательное, поддержка форматов включая SVG

## 7. Очистка кеша API

Добавить очистку кеша при создании/обновлении/удалении бренда:

```php
// В модели Brand добавить в boot метод
Cache::forget('api_brands');
```

## 8. Интеграция в меню

-   Добавить иконку для пункта меню брендов (heroicon-o-tag)
-   Разместить в группе 'Каталог' после категорий (navigationSort = 2)
-   Настроить права доступа через Filament Shield
