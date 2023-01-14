<?php

namespace BernskioldMedia\Autotranslate;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BernskioldMedia\Autotranslate\Commands\AutotranslateCommand;

class AutotranslateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-autotranslate')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-autotranslate_table')
            ->hasCommand(AutotranslateCommand::class);
    }
}
