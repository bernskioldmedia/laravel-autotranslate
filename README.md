# Laravel Autotranslate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskioldmedia/laravel-autotranslate.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-autotranslate)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bernskioldmedia/laravel-autotranslate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskioldmedia/laravel-autotranslate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bernskioldmedia/laravel-autotranslate/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bernskioldmedia/laravel-autotranslate/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskioldmedia/laravel-autotranslate.svg?style=flat-square)](https://packagist.org/packages/bernskioldmedia/laravel-autotranslate)

A Laravel package to automatically translate the application's JSON language files into a chosen language using DeepL.

## Installation

You can install the package via composer:

```bash
composer require bernskioldmedia/laravel-autotranslate
```

You can install the package using the installation command:

```bash
php artisan autotranslate:install
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-autotranslate-config"
```

This is the contents of the published config file:

```php
return [

    /**
     * The source language that your application language is in before translation.
     */
    'source_language' => 'en',

    /**
     * This is your DeepL API key.
     */
    'api_key' => env('AUTOTRANSLATE_DEEPL_KEY'),

    /**
     * DeepL supports settings for each language to translate to.
     * These are the default settings that will be applied
     * unless you override it for a specific language below.
     *
     * See the DeepL PHP SDK for more information: https://github.com/DeepLcom/deepl-php
     */
    'options' => [

        // Choose the formality of the text.
        // This setting is only available for certain languages: https://github.com/DeepLcom/deepl-php#listing-available-languages
        // Available options: 'less', 'more', 'default', 'prefer_less', 'prefer_more'
        'formality' => 'default',

        // Specify how input text should be split into sentences.
        // Available options: 'on', (default) 'off', 'nonewlines'
        'split_sentences' => 'on',

        // Controls automatic-formatting-correction.
        // Set to true to prevent automatic-correction of formatting.
        'preserve_formatting' => false,

        // Type of tags to parse before translation.
        // Available options: 'html', 'xml'
        'tag_handling' => 'html',

        // The ID of the DeepL glossary to use.
        'glossary' => null,
    ],

    'language_options' => [

//      'de' => [
//          'formality' => 'less',
//      ],

    ],

];
```

## Usage

To translate your application's language files, you can use the `autotranslate:translate` command:

```bash
php artisan autotranslate:translate sv
```

The language parameter is required, and should correspond to the language path within your application's languages
folder.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bernskiold Media](https://github.com/bernskioldmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
