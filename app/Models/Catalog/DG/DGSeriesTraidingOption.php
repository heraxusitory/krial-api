<?php


namespace App\Models\Catalog\DG;


use App\Laravue\Models\Traits\Attachmentable;
use App\Models\References\DGVersion;
use Illuminate\Database\Eloquent\Model;

class DGSeriesTraidingOption extends Model
{
    use Attachmentable;

    protected $table = 'dg_series_traiding_options';

    protected $fillable = [
        'dg_series_id',
        'dg_version_id'
    ];

    public function version()
    {
        return $this->hasOne(DGVersion::class, 'id', 'dg_version_id');
    }
}
