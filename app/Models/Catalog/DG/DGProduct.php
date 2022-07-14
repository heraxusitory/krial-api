<?php


namespace App\Models\Catalog\DG;


use App\Models\Property;
use App\Models\PropertyGroup;
use App\Models\PropertyValue;
use App\Models\References\DGEngineManufacture;
use App\Models\References\DGManufacture;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;

class DGProduct extends Model
{
    protected $table = 'dg_products';

    protected $properties = [];

    protected $fillable = [
        'name',
        'second_name',
        'code',
        'series',
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
            ->withPivot('elementable_type', 'value')
            ->wherePivot('elementable_type', '=', DGProduct::class);
        /*->whereExists(
            function ($query) {
                $query->from('property_values')
                    ->where('property_values.elementable_type', DGProduct::class);
            });*/
//            ->where('elementable_type', DGProduct::class);
    }

    public function propertyGroupsWithProperties()
    {
        $property_groups = PropertyGroup::all();
        foreach ($property_groups as $key => $property_group) {
            if ($properties = $this->properties()->where('property_group_id', $property_group->id)->get()) {
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
}
