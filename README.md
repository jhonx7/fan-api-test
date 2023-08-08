# PT FAN Integrasi Teknologi Technical Test - Laravel 10.0
Project dibangun menggunakan Framework Laravel versi 10 dengan starter kit Laravel Breeze yang menyediakan scaffold authentikasi.

## Requirements

- Composer
- Laravel installer

## Installation

```
# Start prepare the environment:
composer install
cp .env.example .env // setup database credentials
php artisan key:generate
php artisan migrate

# Run your server
php artisan serve


# API Documentation
php artisan l5-swagger:generate
Swagger UI : http://127.0.0.1:8000/api/documentation

# Logic Test
Untuk jawaban Logic Test dapat di akses melalui endpoint API yang ada di dokumentasi
atau melalui laravel tinker dengan mengeksekusi fungsi getNumberPairs($array) dan countWord($sentence)


