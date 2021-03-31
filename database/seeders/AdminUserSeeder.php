<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input['name'] = 'admin';
        $input['password'] = Hash::make('secret');
        $input['email'] = 'admin@test.com';
        $input['role'] = 'admin';
        User::create($input);
    }
}
