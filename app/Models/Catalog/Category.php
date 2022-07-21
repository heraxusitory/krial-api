<?php


namespace App\Models\Catalog;


use App\Laravue\Models\Traits\Attachmentable;
use App\Models\Catalog\BlockContainer\BlockContainerProduct;
use App\Models\Catalog\Compressor\CompressorProduct;
use App\Models\Catalog\DG\DGProduct;
use App\Models\Catalog\SurgeProtector\SurgeProtectorProduct;
use App\Models\Catalog\UPS\UPSProduct;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Attachmentable;

    protected $table = 'categories';

    protected $guarded = [];

    protected $casts = [
        'is_root' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected const CATEGORIES = [
        [
            'name' => 'Корневой каталог',
            'code' => 'root',
            'class' => null,
            'is_root' => true,
        ],
        [
            'name' => 'Дизельные электростанции',
            'code' => 'dg',
            'class' => DGProduct::class,
        ],
        [
            'name' => 'Компрессоры',
            'code' => 'compressor',
            'class' => CompressorProduct::class,
        ],
        [
            'name' => 'Источники бесперебойного питания',
            'code' => 'ups',
            'class' => UPSProduct::class,
        ],
        [
            'name' => 'Стабилизаторы напряжения',
            'code' => 'surge_protector',
            'class' => SurgeProtectorProduct::class,
        ],
        [
            'name' => 'Газопоршневые электростанции',
            'code' => 'gas_piston_station',
            'class' => null,
        ],
        [
            'name' => 'Насосные станции',
            'code' => 'pumping_station',
            'class' => null,
        ],
        [
            'name' => 'Холодильное оборудование',
            'code' => 'refrigerator_station',
            'class' => null,
        ],
        [
            'name' => 'Блок контейнеры',
            'code' => 'block_container',
            'class' => BlockContainerProduct::class,
        ],
    ];

    public static function CATEGORIES()
    {
        return self::CATEGORIES;
    }

}
