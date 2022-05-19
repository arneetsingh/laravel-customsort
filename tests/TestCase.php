<?php

namespace ArneetSingh\CustomSort\Tests;

use ArneetSingh\CustomSort\CustomSortServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->loadMigrationsFrom(__DIR__.'/migrations');

//        Factory::guessFactoryNamesUsing(
//            function (string $modelName) {
//                return 'ArneetSingh\\CustomSort\\Tests\\factories' . class_basename($modelName) . 'Factory';
//            }
//        );
        Factory::guessModelNamesUsing(function ($factory) {
            if (class_basename($factory) == 'PostFactory') {
                return Post::class;
            }
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            CustomSortServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');


        $migration = include __DIR__ . '/../database/migrations/create_custom_sorts_table.php.stub';
        $migration->up();

        $migration = include __DIR__ . '/migrations/create_posts_table.php.stub';
        $migration->up();
    }
}
