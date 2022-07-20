<?php


namespace App\Models\Catalog\DG;


use App\Laravue\Models\Traits\Attachmentable;
use App\Models\References\DGVersion;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class DGTradingOption extends Model
{
    use  QueryCacheable, Attachmentable;

    protected $table = 'dg_trading_options';

    protected $fillable = [
        'dg_product_id',
        'dg_version_id',
        'media',
        'price',
    ];

    protected $with = [
        'attachments',
    ];

    public function version()
    {
        return $this->hasOne(DGVersion::class, 'id', 'dg_version_id');
    }
}
