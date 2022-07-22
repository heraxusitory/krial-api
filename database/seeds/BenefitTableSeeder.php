<?php


use App\Models\Catalog\Benefit;
use App\Models\Catalog\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BenefitTableSeeder extends Seeder
{
    public function run()
    {
        $benefits = [
            [
                'name' => 'Высокая стабильность завода КЭС как поставщика',
                'sort' => 100,
            ],
            [
                'name' => 'Предложим оптимальное решение под заданные условия',
                'sort' => 200,
            ],
            [
                'name' => 'Актуальные цены и наличие',
                'sort' => 300,
            ],
        ];

        $category_ids = Category::query()->pluck('id');
        Benefit::query()->truncate();
        foreach ($benefits as $benefit) {
            $benefit = Benefit::query()->create([
                'name' => $benefit['name'],
                'code' => Str::slug($benefit['name']),
                'is_active' => true,
            ]);
            $benefit->categories()->sync($category_ids ?? []);
        }


    }
}
