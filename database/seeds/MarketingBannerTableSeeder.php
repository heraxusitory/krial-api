<?php


use App\Models\Catalog\Category;
use App\Models\Catalog\Marketing\MarketingBanner;
use Illuminate\Database\Seeder;

class MarketingBannerTableSeeder extends Seeder
{
    public function run()
    {
        $banners = [
            [
                'header' => 'Доработка двигателя уменьшит расход топлива на 10%',
                'type' => MarketingBanner::INFORMATION_TYPE(),
                'sort' => 100,
                'url' => 'https://google.com',
                'is_active' => true,
            ],
            [
                'header' => 'Нужен контрагент для плотного сотрудничества?',
                'description' => 'Подберем оборудование с учетов всех условий эксплуатации',
                'type' => MarketingBanner::FORM_TYPE(),
                'sort' => 200,
                'is_active' => true,
                'buttone_text' => 'Оставить заявку',
            ]
        ];

        $category_ids = Category::query()->where('is_root', false)->pluck('id');
        MarketingBanner::query()->truncate();
        foreach ($banners as $banner) {
            $banner = MarketingBanner::query()->create([
                'is_active' => $banner['is_active'],
                'sort' => $banner['sort'],
                'type' => $banner['type'],
                'header' => $banner['header'],
                'url' => $banner['url'] ?? null,
                'button_text' => $banner['button_text'] ?? null,
            ]);
            $banner->categories()->sync($category_ids ?? []);
        }
    }
}
