<?php

namespace ArneetSingh\CustomSort;

use ArneetSingh\CustomSort\Commands\CustomSortCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CustomSortServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-customsort')
            ->hasConfigFile()
//            ->hasViews()
            ->hasMigration('create_custom_sorts_table');
//            ->hasCommand(CustomSortCommand::class);
    }
}
