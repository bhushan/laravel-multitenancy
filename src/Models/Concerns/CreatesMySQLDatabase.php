<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Models\Concerns;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\DatabaseManager;

trait CreatesMySQLDatabase
{
    public static function bootCreatesMySQLDatabase(): void
    {
        if (config('multitenancy.create_mysql_database')) {
            static::created(function ($model) {
                $manager = tap(app(DatabaseManager::class))->beginTransaction();

                $manager->statement("CREATE DATABASE {$model->database}");

                Artisan::call("tenants:artisan migrate:fresh --tenant={$model->id}");

                $manager->commit();
            });
        }
    }
}
