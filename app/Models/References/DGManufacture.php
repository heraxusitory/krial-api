<?php


namespace App\Models\References;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class DGManufacture extends Model
{
    use QueryCacheable;

    protected $table = 'dg_manufactures';

    protected $fillable = [
        'name',
        'code',
        'country',
        'description',
    ];
}
