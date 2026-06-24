<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::updateOrCreate(
            ['id' => 1],
            ['name' => 'Debug Test']
        );

        Category::updateOrCreate(
            ['id' => 2],
            ['name' => 'IELTS Speaking']
        );
    }
}
