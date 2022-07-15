<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'name',
        'code',
        'is_main_in_card',
        'is_main_in_group',
        'is_main_in_header',
        'is_filterable',
        'property_group_id',
        'description',
    ];

    protected $casts = [
        'is_main_in_card' => 'boolean',
        'is_main_in_group' => 'boolean',
        'is_main_in_header' => 'boolean',
        'is_filterable' => 'boolean',
    ];

    public function value()
    {
        return $this;
    }

    public function group()
    {
        return $this->hasOne(PropertyGroup::class, 'id', 'property_group_id');
    }
}
