# Audit Trail

A library package used to logs activities/transactions made in your web application.

### Installation

1. Run in terminal: 

```bash
composer require rollswan/audit-trail
```

2. Register the package 

- *Laravel 5.5 and up* <br>
Uses package auto discovery feature, no need to edit the `config/app.php` file.

- *Laravel 5.4 and below* <br>
Register the package with laravel in `config/app.php` under `providers` with the following:

```php
'providers' => [
    Rollswan\AuditTrail\Providers\AuditTrailServiceProvider::class,
];
```

3. Migrate `audit_trails` table by running:

```bash
php artisan migrate
```

4. Publish config file by running:

```bash
php artisan vendor:publish --tag=AuditTrail
```

### How to use?

1) Use `audit-trail` via middleware

```php
Route::group(['middleware' => ['web', 'audit-trail']], function () {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
});
```