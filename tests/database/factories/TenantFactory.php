<?php declare(strict_types=1);

use Faker\Generator;
use Enlight\Multitenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Tenant::class, fn (Generator $faker) => [
    'name' => $faker->name,
    'subdomain' => $faker->unique()->word,
    'domain' => $faker->unique()->domainName,
    'database' => $faker->unique()->word,
]);
