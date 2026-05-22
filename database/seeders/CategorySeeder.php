<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Venue', 'icon' => 'home'],
            ['name' => 'Catering', 'icon' => 'utensils'],
            ['name' => 'Photography', 'icon' => 'camera'],
            ['name' => 'Decoration', 'icon' => 'flower'],
            ['name' => 'Makeup Artist', 'icon' => 'brush'],
            ['name' => 'Wedding Organizer', 'icon' => 'calendar'],
            ['name' => 'Music & Entertainment', 'icon' => 'music'],
            ['name' => 'Attire & Jewelry', 'icon' => 'shopping-bag'],
        ];

        foreach ($categories ?? [] as $category) {
            Category::create($category);
        }
    }
}