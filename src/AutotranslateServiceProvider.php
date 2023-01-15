<?php

namespace BernskioldMedia\Autotranslate;

use BernskioldMedia\Autotranslate\Commands\TranslateFile;
use DeepL\Translator;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasCommand(TranslateFile::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile()
                    ->askToStarRepoOnGitHub('bernskioldmedia/laravel-autotranslate');
            });
    }

    public function bootingPackage()
    {
        $this->app->bind(Translator::class, function () {
            return new Translator(config('autotranslate.api_key'));
        });
    }
}
