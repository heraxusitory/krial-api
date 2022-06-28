<?php


namespace App\Models\References;


use Illuminate\Database\Eloquent\Model;

class DGManufacture extends Model
{
    protected $table = 'dg_manufactures';

    protected $fillable = [
        'name',
        'code',
        'country',
        'description',
    ];
}
