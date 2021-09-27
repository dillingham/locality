# Locality

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dillingham/locality.svg?style=flat-square)](https://packagist.org/packages/dillingham/locality)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/dillingham/locality/run-tests?label=tests)](https://github.com/dillingham/locality/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/dillingham/locality/Check%20&%20fix%20styling?label=code%20style)](https://github.com/dillingham/locality/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dillingham/locality.svg?style=flat-square)](https://packagist.org/packages/dillingham/locality)

---

A Laravel package that automatically normalizes address data. Instead of storing city state & zipcodes repeatedly, create tables and reference the foreign key. This package accepts the string representation, checks if it exists or creates it and adds the relationship. This package also provides accessors to make it feel as though you aren't even normalizing.

---

## Installation

You can install the package via composer:

```bash
composer require dillingham/locality
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Dillingham\Locality\LocalityServiceProvider" --tag="locality-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Dillingham\Locality\LocalityServiceProvider" --tag="locality-config"
```

Add the following columns to your model's migration:

```php
$table->addAddress();
```
Which is just a shorthand for adding these columns:
```php
$table->string('formatted_address');
$table->string('address_1')->nullable();
$table->string('address_2')->nullable();
$table->foreignId('admin_level_3_id')->nullable()->index();
$table->foreignId('admin_level_2_id')->index();
$table->foreignId('admin_level_1_id')->index();
$table->foreignId('postal_code_id')->index();
$table->foreignId('country_id')->index();
```

This and the 4 tables will be migrated:
```
php artisan migrate
```

Then add the `HasAddress` trait:

```php
<>php

namespace App\Models;

use Dillingham\Locality\HasAddress;

class Profile extends Model
{
  use HasAddress;
}
```

## Usage

```php
Profile::create([
    'address_1' => '104 India St',
    'address_2' => 'Apt #3L',
    'admin_level_2' => 'Brookyln',    
    'admin_level_1' => 'NY',
    'postal_code' => '11222',
]);
```
Automatically the trait will use firstOrCreate when storing Profile

```php
'admin_level_2' => 'Brookyln'
```
becomes the foreign id of Brooklyn in the `admin_level_2` table

```php
'admin_level_2_id' => 332
```

## Access Values

Access the string values of the relationships via accessors:

```php
$profile->admin_level_2 == 'Brooklyn'
$profile->admin_level_1 == 'NY'
```
> These accessors call relationships behind the scenes, eager load in collections

Note: the full address formatting is statically stored while saving:
```php
$profile->formatted_address == '104 India St, #3l Brooklyn, NY 11222`
```

# Bonus

The following are opt in loosely related features;

### Dependent Filters

Here are some api routes for filtering models by localtion info

```php
Route::localityDepenentOptions();
```
> The following assumes routes/api.php and prefixed from RouteServiceProvider.php
```
GET /api/locality/countries
```
```json
{
    "data": [
        {
            "value": 1,
            "display": "US"
        }
    ]
}
```
```
GET /api/locality/admin_level_2?country_id=1
```
```json
{
    "data": [
        {
            "value": 1,
            "display": "NY"
        }
    ]
}
```
```
GET /api/locality/admin_level_1?admin_level_2_id=1
```
```json
{
    "data": [
        {
            "value": 1,
            "display": "Brooklyn"
        }
    ]
}
```
```
GET /api/locality/postal_code?admin_level_1_id=1
```
```json
{
    "data": [
        {
            "value": 1,
            "display": "11222"
        }
    ]
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Brian Dillingham](https://github.com/dillingham)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
