<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class PropertyValue extends Model
{
    use QueryCacheable;

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
