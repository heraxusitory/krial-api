<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'name',
        'code',
        'property_group_id',
        'description',
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
