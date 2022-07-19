<?php


namespace App\Models\Catalog\DG;


use App\Laravue\Models\Traits\Attachmentable;
use Illuminate\Database\Eloquent\Model;

class DGOption extends Model
{
    use Attachmentable;

    protected $table = 'dg_options';

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'dg_option_group_id',
    ];

    public function group()
    {
        return $this->belongsTo(DGOptionGroup::class, 'dg_option_group_id', 'id');
    }
}
