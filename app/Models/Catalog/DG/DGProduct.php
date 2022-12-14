<?php


namespace App\Models\Catalog\DG;


use App\Models\Property;
use App\Models\PropertyGroup;
use App\Models\PropertyValue;
use App\Models\References\DGEngineManufacture;
use App\Models\References\DGManufacture;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class DGProduct extends Model
{
    use QueryCacheable, Sortable, Searchable;

//    protected $cacheFor = 180;

    protected $table = 'dg_products';

//    protected $properties = [];

    protected $fillable = [
        'name',
        'second_name',
        'code',
        'series_id',
        'sort',
        'is_active',
        'manufacture_id',
        'engine_manufacture_id',
        'media',
        'description',
        'internal_vendor_code',
        'allgen_vendor_code',
        'allgen_url',
        'provider_vendor_code',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, PropertyValue::class, 'elementable_id', 'property_id')
            ->withPivot('value', 'slug');
    }

    public function propertyGroupsWithProperties($cache_enabled = false)
    {
        $property_groups_query = PropertyGroup::query();
//        $property_groups_query = $cache_enabled ? $property_groups_query/*->cacheFor(180)*/ : $property_groups_query;
        $property_groups = $property_groups_query->get();

        foreach ($property_groups as $key => $property_group) {
            $properties = $this->properties->where('property_group_id', $property_group->id);
//            $properties_query = $this->properties->where('property_group_id', $property_group->id);
//            $properties_query = $cache_enabled ? $properties_query/*->cacheFor(180)*/ : $properties_query;
            if ($properties->isNotEmpty()) {
                $property_group->properties = $properties;
            } else {
                unset($property_groups[$key]);
            }
        }
        return $property_groups;
    }

    public function traiding_options()
    {
        return $this->hasMany(DGTradingOption::class, 'dg_product_id', 'id');
    }

    public function manufacture()
    {
        return $this->hasOne(DGManufacture::class, 'id', 'manufacture_id');
    }

    public function engine_manufacture()
    {
        return $this->hasOne(DGEngineManufacture::class, 'id', 'engine_manufacture_id');
    }

    public function series()
    {
        return $this->belongsTo(DGSeries::class, 'series_id', 'id');
    }
}
