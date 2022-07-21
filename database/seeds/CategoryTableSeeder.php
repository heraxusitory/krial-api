<?php


use App\Models\Catalog\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::CATEGORIES();
        foreach ($categories as $category) {
            Category::query()->updateOrCreate([
                'code' => $category['code'],
            ], [
                'name' => $category['name'],
                'code' => $category['code'],
                'categoriable_type' => $category['class'],
                'is_root' => array_key_exists('is_root', $category) ? $category['is_root'] : false,
                'is_active' => array_key_exists('is_root', $category),
            ]);
        }
    }
}
