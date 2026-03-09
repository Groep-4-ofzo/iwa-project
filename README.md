## getting stared

zoek waar php.ini is

'''bash
php --ini
'''

zoek naar:

Configuration File (php.ini) Path: /etc/php/8.2/cli

Loaded Configuration File:         /etc/php/8.2/cli/php.ini


open het bestand en vink pdo_mysql aan

```bash
composer run setup
npm install && npm run build
composer run dev
```


## Migrate
```bash
php artisan migrate
```
make a migration 
```bash
php artisan make:migration create_[tableName]_table
```
wanneer je wilt updaten
```bash
php artisan migrate:refresh
```
[Docs](https://laravel.com/docs/12.x/migrations)

## Run
```bash
npm install && npm run build
composer run dev
```

## routes
iwa-project/routes/web.php
routes toevoegen in web.php
[Docs](https://laravel.com/docs/12.x/routing)


## views (frontend)
iwa-project/resources
[Docs](https://laravel.com/docs/12.x/views)

## controllers
iwa-project/app/http/Controllers
```bash
php artisan make:controller ControllerName
```
[Docs](https://laravel.com/docs/12.x/controllers)

## models (database information)
iwa-project/app/http/Models
```bash
php artisan make:model ModelName
```
[Docs](https://laravel.com/docs/12.x/eloquent)


