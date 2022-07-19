<?php


namespace App\Models\Catalog\DG;


use Illuminate\Database\Eloquent\Model;

class DGOption extends Model
{
    protected $table = 'dg_options';

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'dg_option_group_id',
    ];
}
