<?php


namespace App\Models\References;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class DGEngineManufacture extends Model
{
    use QueryCacheable;

    protected $table = 'dg_engine_manufactures';

    protected $fillable = [
        'name',
        'code',
        'country',
        'description',
    ];
}
