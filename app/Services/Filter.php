<?php


namespace App\Services;


use App\Models\Catalog\DG\DGProduct;
use App\Models\Property;
use App\Models\PropertyValue;
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
        foreach ($this->filters as $filter) {
            $property = Property::query()->where(['is_filterable' => true, 'code' => $filter['code']])->first();
            if (!is_null($property)) {
                $filter_type = $property->filter_type;
            } else
                continue;
            $this->dg_product_ids = match ($filter['entity_type']) {
                'property' => $dg_query->whereHas('properties', function (Builder $query) use ($filter_type, $filter) {
                    $query = $query->where('is_filterable', '=', true)
                        ->where('code', '=', $filter['code']);

                    switch ($filter_type) {
                        case 'list':
                            if (isset($filter['values']) && is_array($filter['values'])) {
                                $query->whereIn('value', $filter['values']);
                            }
                            break;
                        case 'range':
                            if (isset($filter['values']['min']) && isset($filter['values']['max'])) {
                                $query->whereBetween('value', [$filter['values']['min'], $filter['values']['max']]);
                            }
                            break;
                        case 'input':
                            if (isset($filter['values']) && !is_array($filter['values'])) {
                                $query->where('value', 'ILIKE', $filter['values']);
                            }
                            break;
                    }
                })->pluck('id'),
            };
        }
        $this->query = $dg_query;
        $this->setAvailableFilterParams();
        return $this;
    }

    private function setAvailableFilterParams()
    {
        $properties = Property::query()->where('is_filterable', true)->get();
        $this->available_filter_params = $properties->map(function ($property) {
            $query = PropertyValue::query()
                ->where(['elementable_type' => DGProduct::class,])
                ->where('property_id', $property->id);
            if (!empty($this->filters))
                $query->whereIn('elementable_id', $this->dg_product_ids);

            $filter_item_callback = match ($property->filter_type) {
                Property::FILTER_TYPE_LIST() => function () use ($property, $query) {
                    $values = $query->pluck('value');
                    return [
                        'code' => $property->code,
                        'name' => $property->name,
                        'entity_type' => 'property',
                        'values' => $values,
                        'type' => 'list',
                    ];
                },
                Property::FILTER_TYPE_RANGE() => function () use ($property, $query) {
                    $min = $query->min('value');
                    $max = $query->max('value');
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
                    $min = $query->min('value');
                    $max = $query->max('value');
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
                }
            };
            return $filter_item_callback();
        });
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
