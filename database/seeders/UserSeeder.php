<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $input['name'] = 'sanel';
        $input['password'] = Hash::make('secret');
        $input['email'] = 'sanel@test.com';
        $input['role'] = 'manager';
        User::create($input);

        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {

            User::create([
                'name' => $faker->userName,
                'password' => Hash::make('secret'),
                'email' => $faker->unique()->email,
                'role' => 'customer',
            ]);
        }
    }
}
