# Инструкция по развертыванию сайта на виртуальном хостинге

## 1. Подготовка к развертыванию

### 1.1. Требования к хостингу

-   PHP 8.1 или выше
-   Composer
-   Git
-   MySQL или PostgreSQL (для хранения данных и кэша)
-   SSH доступ

### 1.2. Подготовка SSH доступа

```bash
# Подключение к серверу
ssh username@your-server-ip
```

## 2. Установка проекта

### 2.1. Клонирование репозитория

```bash
# Переход в директорию веб-сервера
cd /path/to/public_html

# Клонирование репозитория
git clone https://github.com/your-username/holodlab-admin.git .
```

### 2.2. Установка зависимостей

```bash
# Установка PHP зависимостей
composer install --no-dev --optimize-autoloader

# Установка Node.js зависимостей и сборка фронтенда
npm install
npm run build
```

## 3. Настройка окружения

### 3.1. Настройка .env файла

```bash
# Копирование примера конфигурации
cp .env.example .env

# Генерация ключа приложения
php artisan key:generate

# Редактирование .env файла
nano .env
```

Необходимо настроить следующие параметры в .env файле:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3.2. Настройка прав доступа

```bash
# Установка прав на директории
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Создание символической ссылки для storage
php artisan storage:link
```

## 4. Настройка базы данных

```bash
# Выполнение миграций
php artisan migrate --force

# Заполнение начальными данными
php artisan db:seed --force
```

## 5. Оптимизация приложения

```bash
# Очистка и кэширование конфигурации
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Оптимизация автозагрузки
composer dump-autoload --optimize
```

## 6. Настройка веб-сервера

### 6.1. Конфигурация Nginx

Создайте файл конфигурации для вашего домена:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/public_html/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6.2. SSL сертификат

Установите SSL сертификат через Let's Encrypt:

```bash
certbot --nginx -d your-domain.com
```

## 7. Проверка работоспособности

-   Откройте сайт в браузере: https://your-domain.com
-   Проверьте работу админ-панели: https://your-domain.com/admin
-   Убедитесь, что все статические файлы загружаются корректно
-   Проверьте работу загрузки файлов через админ-панель

## 8. Обновление приложения

```bash
# Включение режима обслуживания
php artisan down

# Получение обновлений
git pull origin main

# Обновление зависимостей
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Обновление базы данных
php artisan migrate --force

# Очистка кэша
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Выключение режима обслуживания
php artisan up
```
