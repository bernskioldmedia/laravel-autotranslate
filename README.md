# A Laravel package to automatically translate the application's language files into a chosen language using DeepL.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskioldmedia/laravel-autotranslate.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-autotranslate)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bernskioldmedia/laravel-autotranslate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskioldmedia/laravel-autotranslate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bernskioldmedia/laravel-autotranslate/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bernskioldmedia/laravel-autotranslate/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskioldmedia/laravel-autotranslate.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-autotranslate)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require bernskioldmedia/laravel-autotranslate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-autotranslate-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-autotranslate-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$autotranslate = new BernskioldMedia\Autotranslate();
echo $autotranslate->echoPhrase('Hello, BernskioldMedia!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Bernskiold Media](https://github.com/bernskioldmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
