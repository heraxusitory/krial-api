<?php


namespace App\Models\Catalog\DG;


use App\Models\Property;
use App\Models\PropertyValue;
use Illuminate\Database\Eloquent\Model;

class DGProduct extends Model
{
    protected $table = 'dg_products';
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

    public function traiding_options()
    {
        return $this->hasMany(DGTradingOption::class, 'dg_product_id', 'id');
    }
}
