<?php


namespace App\Services;


use App\Models\Catalog\DG\DGProduct;
use App\Models\Catalog\DG\DGTradingOption;
use App\Models\Property;
use App\Models\PropertyValue;
use App\Models\References\DGEngineManufacture;
use App\Models\References\DGManufacture;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    private Builder $builder;
    private \Illuminate\Support\Collection|array $dg_product_ids = [];
    /**
     * @var Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public \Illuminate\Database\Eloquent\Collection|array|\Illuminate\Support\Collection $available_filter_params = [];
    /**
     * @var Builder
     */
    public Builder $query;

    public function __construct(private $filters)
    {
    }

    public function filter()
    {
        $dg_query = DGProduct::query();
        $properties = Property::query()->where('is_filterable', true)->cacheFor(180)->cacheTags(["search_properties_for_filter"])->get();
        foreach ($this->filters as &$filter) {
            if ($filter['entity_type'] === 'property') {
                $property = $properties->where('code', $filter['code'])->first();
                if (!is_null($property)) {
                    $filter_type = $property->filter_type;
                } else
                    continue;
//                $filter['dg_product_ids'] = match ($filter['entity_type']) {
                $dg_query->whereHas('properties', function (Builder $query) use ($filter_type, $filter) {
                    $query = $query->where('is_filterable', '=', true)
                        ->where('code', '=', $filter['code']);

                    switch ($filter_type) {
                        case 'list':
                            if (isset($filter['values']) && is_array($filter['values'])) {
                                $query->whereIn('slug', $filter['values']);
                            }
                            break;
                        case 'range':
                            if (isset($filter['values']['min']) && isset($filter['values']['max'])) {
                                $query->whereBetween('value', [(string)($filter['values']['min']), (string)$filter['values']['max']]);
                            }
                            break;
                        case 'input':
                            if (isset($filter['values']) && !is_array($filter['values'])) {
                                $query->where('slug', 'LIKE', $filter['values']);
                            }
                            break;
                    }
                });
//
//                    'manufacture' => $dg_query->whereHas('manufacture', function (Builder $query) use ($filter) {
//                        $query->whereIn('name', $filter['values']);
//                    }),
//
//                    'engine_manufacture' => $dg_query->whereHas('engine_manufacture', function (Builder $query) use ($filter) {
//                        $query->whereIn('name', $filter['values']);
//                    }),
//
//                    'price' => $dg_query->whereHas('traiding_options', function (Builder $query) use ($filter) {
//                        $query->whereIn('price', $filter['values']);
//                    }),

//                };
            } elseif ($filter['entity_type'] === 'manufacture') {
                $dg_query->whereHas('manufacture', function (Builder $query) use ($filter) {
                    $query->whereIn('code', $filter['values']);
                });
            } elseif ($filter['entity_type'] === 'engine_manufacture') {
                $dg_query->whereHas('engine_manufacture', function (Builder $query) use ($filter) {
                    $query->whereIn('code', $filter['values']);
                });
            } elseif ($filter['entity_type'] === 'price') {
                $dg_query->whereHas('traiding_options', function (Builder $query) use ($filter) {
                    $query->whereIn('price', [(int)$filter['values']['min'], (int)$filter['values']['max']]);
                });
            }
        }
        $this->query = $dg_query;
        $this->setAvailableFilterParams();
        return $this;
    }

    private function setAvailableFilterParams()
    {
        $properties = Property::query()->where('is_filterable', true)->cacheFor(180)->cacheTags(["properties_for_available_filters"])->get();
        $this->available_filter_params = $properties->map(function ($property) {
            $query = PropertyValue::query()
                ->cacheFor(180)
                ->where(['elementable_type' => DGProduct::class,])
                ->where('property_id', $property->id);
//            if (!empty($this->filters))
//                $query->whereIn('elementable_id', $this->dg_product_ids);

            $filter_item_callback = match ($property->filter_type) {
                Property::FILTER_TYPE_LIST() => function () use ($property, $query) {
                    $values = $query->pluck('value', 'slug')->unique()->sort();
                    return [
                        'code' => $property->code,
                        'name' => $property->name,
                        'entity_type' => 'property',
                        'values' => $values,
                        'type' => 'list',
                    ];
                },
                Property::FILTER_TYPE_RANGE() => function () use ($property, $query) {
//                    dump($query->pluck('value')->min());
//                    dd($query->pluck('value')->max());
//                    $min = $query->min('value');
//                    $max = $query->max('value');
                    $min = $query->pluck('value')->min();
                    $max = $query->pluck('value')->max();
                    return [
                        'code' => $property->code,
                        'name' => $property->name,
                        'entity_type' => 'property',
                        'values' => [
                            'min' => $min,
                            'max' => $max,
                        ],
                        'type' => 'range',
                    ];
                },
                Property::FILTER_TYPE_INPUT() => function () use ($property, $query) {
                    return [
                        'code' => $property->code,
                        'name' => $property->name,
                        'entity_type' => 'property',
                        'type' => 'input',
                    ];
                }
            };
            return $filter_item_callback();
        });

        $this->available_filter_params[] =
            [
                'code' => 'manufacture',
                'name' => '??????????????????????????',
                'entity_type' => 'manufacture',
                'values' => DGManufacture::query()->orderBy('name')->pluck('name', 'code')->unique(),
                'type' => 'list',
            ];

        $this->available_filter_params[] =
            [
                'code' => 'engine_manufacture',
                'name' => '??????????????????',
                'entity_type' => 'engine_manufacture',
                'values' => DGEngineManufacture::query()->orderBy('name')->pluck('name', 'code')->unique(),
                'type' => 'list',
            ];

        $traiding_options = DGTradingOption::query()/*->orderBy('price')->pluck('price')->unique()*/
        ;

        $this->available_filter_params[] =
            [
                'code' => 'price',
                'name' => '????????',
                'entity_type' => 'price',
                'values' => [
                    'min' => $traiding_options->min('price'),
                    'max' => $traiding_options->max('price')
                ],
                'type' => 'range',
            ];
    }

//    public function getAllAvailableFilterParams()
//    {
//        $properties = Property::query()->where('is_filterable', true)->get();
//        return $properties->map(function ($property) {
//            $query = PropertyValue::query()
//                ->where(['elementable_type' => DGProduct::class,])->where('property_id', $property->id);
//
//            $filter_item_callback = match ($property->filter_type) {
//                Property::FILTER_TYPE_LIST() => function () use ($property, $query) {
//                    $values = $query->pluck('value');
//                    return [
//                        'code' => $property->code,
//                        'name' => $property->name,
//                        'entity_type' => 'property',
//                        'values' => $values,
//                        'type' => 'list',
//                    ];
//                },
//                Property::FILTER_TYPE_RANGE() => function () use ($property, $query) {
//                    $min = $query->min('value');
//                    $max = $query->max('value');
//                    return [
//                        'code' => $property->code,
//                        'name' => $property->name,
//                        'values' => [
//                            'min' => $min,
//                            'max' => $max,
//                        ],
//                        'type' => 'range',
//                    ];
//                },
//                Property::FILTER_TYPE_INPUT() => function () use ($property, $query) {
//                    $min = $query->min('value');
//                    $max = $query->max('value');
//                    return [
//                        'code' => $property->code,
//                        'name' => $property->name,
//                        'values' => [
//                            'min' => $min,
//                            'max' => $max,
//                        ],
//                        'type' => 'range',
//                    ];
//                }
//            };
//            return $filter_item_callback();
//        });
//    }
}
