<?php

namespace Database\Seeders;

use TheFramework\Database\Seeder;
use Faker\Factory;
use TheFramework\Helpers\Helper;

class Seeder_2026_01_02_205046_UserSeeder extends Seeder
{

    public function run()
    {
        $faker = Factory::create();
        Seeder::setTable('users');

        for ($i = 0; $i < 10; $i++) {
            Seeder::create([
                [
                    'uid' => Helper::uuid(20),
                    'name' => $faker->name(),
                    'email' => $faker->email(),
                    'password' => Helper::hash_password($faker->name()),
                ]
            ]);
        }
    }
}
