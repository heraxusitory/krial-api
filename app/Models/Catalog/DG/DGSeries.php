<?php


namespace App\Models\Catalog\DG;


use App\Laravue\Models\Traits\Attachmentable;
use Illuminate\Database\Eloquent\Model;

class DGSeries extends Model
{
    protected $table = 'dg_series';

    protected $fillable = [
        'name',
        'code',
    ];

    public function traiding_options()
    {
        return $this->hasMany(DGSeriesTraidingOption::class, 'dg_series_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(DGProduct::class, 'id', 'series_id');
    }
}
