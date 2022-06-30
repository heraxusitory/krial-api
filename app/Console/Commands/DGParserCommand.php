<?php

namespace App\Console\Commands;

use App\Models\Catalog\DG\DGProduct;
use App\Models\Catalog\DG\DGTradingOption;
use App\Models\Property;
use App\Models\PropertyGroup;
use App\Models\PropertyValue;
use App\Models\References\DGEngineManufacture;
use App\Models\References\DGManufacture;
use App\Models\References\DGVersion;
use App\Services\DGParser\DGParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sunra\PhpSimple\HtmlDomParser;

class DGParserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dg:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $dg_parser = ((new DGParser(env('ALLGEN_DOMAIN') . 'generator'))
            ->setManufactureSelector('#catTable .manufacturers'))
            ->start();

        foreach ($dg_parser->getBrands() as $brand) {
            $dg_manufacture = DGManufacture::query()->updateOrCreate([
                'name' => $brand->getName(),
                'code' => \Illuminate\Support\Str::slug($brand->getName()),
                'country' => $brand->getCountry(),
                'description' => $brand->getBrandDescription(),
            ]);

//            $this->info('Производитель ДГУ: ' . $dg_manufacture->name . ' сохранен' . "\n");

            foreach ($brand->getBrandProducts()->find('.manufacturer-products-table tr') as $row) {
                $model = $row->find('td.cell-text-left > a.manufacturer-products-item__title');
                if (empty($model))
                    continue;
                $product = $model[0];

                $response = (new Client())->get($product->href)->getBody()->getContents();
                $brand_product_parser = HtmlDomParser::str_get_html($response);

                $stat_block_items = $brand_product_parser->find('.product-stats-block-list .product-stats-item');

                $product_description = $brand_product_parser->find('.product-tabs-body > .product-tabs-section > p')[0]->innertext();
                $product_name = $brand_product_parser->find('.content .product h2.product__title')[0]->innertext();
                $product_code_name = \Illuminate\Support\Str::slug($product_name);
                $allgen_vendor_code = $brand_product_parser->find('.product-stats-block-list > .product-stats-item')[0];
                if ($allgen_vendor_code->first_child()->innertext() === 'Артикул') {
                    $allgen_vendor_code = $allgen_vendor_code->last_child()->innertext();
                } else {
                    $allgen_vendor_code = null;
                }
                $url_allgen = $product->href;
                $dg_product_model = DGProduct::query()->updateOrCreate([
                    'code' => $product_code_name,
                ],
                    [
                        'name' => $product_name,
//                   'second_name',
//                   'series_id',
//                   'sort',
                        'description' => $product_description,
                        'allgen_vendor_code' => $allgen_vendor_code,
                        'allgen_url' => $url_allgen,
                    ]);
//                $this->info('Продукт ДГУ: ' . $dg_product_model->name . ' сохранен' . "\n");
                if (count($brand_product_parser->find('.product-variants-row')[0]->children) > 5) {
                    Log::debug('Существует более 5 вариаций исполнения: ' . $url_allgen);
                }

                $version_images = $brand_product_parser->find('.product-variants-row > .product-variants-col > img');
                $dg_version_open = DGVersion::where('code', DGVersion::VERSION_OPEN())->firstOrFail();
//            dd($version_images[0]->name);
                $trading_option = DGTradingOption::query()->updateOrCreate(
                    [
                        'dg_product_id' => $dg_product_model->id,
                        'dg_version_id' => $dg_version_open->id
                    ],
                    [
                        'media' => json_encode([
                            $version_images[0]->name
                        ])
                    ]);

//                $this->info('Торговое предложение ОТКРЫТОГО ТИПА для продукта ДГУ: ' . $dg_product_model->name . ' сохранено' . "\n");

                $dg_version_in_case = DGVersion::where('code', DGVersion::VERSION_IN_CASE())->firstOrFail();

                DGTradingOption::query()->updateOrCreate(
                    [
                        'dg_product_id' => $dg_product_model->id,
                        'dg_version_id' => $dg_version_in_case->id
                    ],
                    [
                        'media' => json_encode([
                            $version_images[1]->name
                        ])
                    ]);

//                $this->info('Торговое предложение В КОЖУХЕ для продукта ДГУ: ' . $dg_product_model->name . ' сохранено' . "\n");


                $charachter_blocks = $brand_product_parser->find('.product-stats-block');

                //Блок оснвные характеристики
                foreach ($charachter_blocks as $block) {
                    $group_block_title = $block->find('.product-stats-block__title');
                    $group_name = !empty($group_block_title) ? $group_block_title[0]->innertext() : 'Основные характеристики';

//                dump($group_name);
                    $group_model = PropertyGroup::query()->updateOrCreate([
                        'name' => $group_name,
                    ]);

//                    $this->info("Группа свойств {$group_model->name} для продукта ДГУ: $dg_product_model->name сохранена" . "\n");


                    foreach ($block->find('.product-stats-block-list')[0]->children as $property) {
                        $desc_target = $property->find('.product-stats-item__title > a');
                        $description = null;
                        if (!empty($desc_target)) {
                            $desc_target_value = $desc_target[0]->getAttribute('data-remodal-target');
                            $description = $brand_product_parser->find("[data-remodal-id=\"$desc_target_value\"] p")[0]->innertext();
                        }
                        $property_name = $property->first_child()->innertext();
                        $property_name = trim(preg_replace("/[;].*$|&nbsp/", '', $property_name));

//                    dump($property_name);
                        $prop_value = $property->last_child()->innertext();
//                    #<a href.*">{0}#
//                    dump($prop_value);
                        $prop_value = trim(preg_replace('!<([^>]+)>| [(].*[)]!', '', $prop_value));
                        if ($property_name === 'Производитель двигателя') {
                            $engine_country = trim(preg_replace("/^.*[(]|[)]/", '', $property->last_child()->innertext()));
                            $engine_manufacture = DGEngineManufacture::updateOrCreate([
                                'code' => Str::slug($prop_value)
                            ], [
                                'name' => $prop_value,
                                'country' => $engine_country
                            ]);
//                            $this->info("Производитель двигателя {$engine_manufacture->name} для продукта ДГУ: $dg_product_model->name сохранен" . "\n");
                        }

                        $property = Property::updateOrCreate([
                            'code' => Str::slug($property_name)
                        ], [
                            'name' => $property_name,
                            'property_group_id' => $group_model->id,
                            'description' => $description ?? null,
                        ]);

                        $property_value = PropertyValue::updateOrCreate([
                            'elementable_type' => DGProduct::class,
                            'elementable_id' => $dg_product_model->id,
                            'property_id' => $property->id,
                            'value' => $prop_value,
                        ]);

//                        $this->info("Свойство {$property->name} = {$property_value->value} для продукта ДГУ группы {$group_model->name}: $dg_product_model->name сохранено" . "\n");

                    }
                }
            }
        }
        return 0;
    }
}
