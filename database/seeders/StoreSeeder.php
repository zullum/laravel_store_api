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

        foreach ($users as $key => $value) {
            $input['name'] = 'Bingo'.$key;
            $input['address'] = $faker->address;
            $input['manager_id'] = $value;

            Store::create($input);
        }

    }
}
