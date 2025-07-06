<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Deploy

1. `composer install`
2. `npm i`
3. Despues de instalar las dependencias modificar el archivo: `vendor\spatie\laravel-permission\src\Models\Permission.php` que permitir que los permission se relacion con tenant_id y no los marque como duplicados, en la funcion `create`
```php
$permission = static::getPermission([
    'team_id' => $attributes['team_id'],
    'name' => $attributes['name'],
    'guard_name' => $attributes['guard_name']
]);
```
4. `php artisan migrate --seed`
