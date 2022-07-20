<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class PropertyGroup extends Model
{
    use QueryCacheable;

    protected $table = 'property_groups';

    protected $fillable = [
        'name',
        'code',
    ];
}
