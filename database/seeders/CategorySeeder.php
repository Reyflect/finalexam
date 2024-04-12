<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Foods', 'Clothes', 'Toys'
        Category::create([
            'name' => 'Foods',
            'description' => 'lorem ipsum',
            'image' => 'images/default.png'
        ]);
        Category::create([
            'name' => 'Clothes',
            'description' => 'lorem ipsum',
            'image' => 'images/default.png'
        ]);
        Category::create([
            'name' => 'Toys',
            'description' => 'lorem ipsum',
            'image' => 'images/default.png'
        ]);
    }
}
