<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Store;
use App\Models\User;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Factory::create();
        // geeting the all available managers
        $users = User::where('role', 'manager')->get()->pluck('id')->toArray();

        $input['name'] = 'Bingo';
        $input['address'] = $faker->address;
        // picking a random manager for our first store
        $input['manager_id'] = $faker->randomElement($users);

        Store::create($input);
    }
}
