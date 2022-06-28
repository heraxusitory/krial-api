<?php


namespace App\Models\References;


use Illuminate\Database\Eloquent\Model;

class DGEngineManufacture extends Model
{
    protected $table = 'dg_engine_manufactures';

    protected $fillable = [
        'name',
        'code',
        'country',
        'description',
    ];
}
