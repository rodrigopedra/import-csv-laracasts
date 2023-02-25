<?php

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('app:generate', function () {
    $faker = Faker\Factory::create();

    $file = new \SplFileObject(\storage_path('app/large-sample.csv'), 'w+b');
    $file->fputcsv(['last_name', 'first_name', 'phone', 'email']);

    foreach (\range(1, 1_300_000) as $index) {
        $file->fputcsv([
            $faker->lastName,
            $faker->firstName,
            $faker->phoneNumber,
            $faker->email,
        ]);

        if (($index + 1) % 1000 === 0) {
            $this->info(\number_format($index + 1));
        }
    }
});
