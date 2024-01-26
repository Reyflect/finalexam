<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = bcrypt('abc');
        User::create([
            'username' => 'Rey',
            'email' => 'rey@gmail.com',
            'password' => "$password",
        ]);
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => "$password",
        ]);
    }
}
