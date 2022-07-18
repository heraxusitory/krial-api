<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PropertyValue extends Model
{
    protected $table = 'property_values';

    protected $fillable = [
        'elementable_type',
        'elementable_id',
        'property_id',
        'slug',
        'value',
    ];

//    public function valueable()
//    {
//        return $this->morphTo();
//    }
}
