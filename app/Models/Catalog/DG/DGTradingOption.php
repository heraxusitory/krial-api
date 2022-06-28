<?php


namespace App\Models\Catalog\DG;


use Illuminate\Database\Eloquent\Model;

class DGTradingOption extends Model
{
    protected $table = 'dg_trading_options';

    protected $fillable = [
        'dg_product_id',
        'dg_version_id',
        'media',
        'price',
    ];
}
