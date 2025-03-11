<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = [
            'first_name' => 'Admin',
            'role' => 1,
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ];

        User::create($admin);

        //User::where('id', 1)->update(['password' => Hash::make('admin')]);
    }
}
