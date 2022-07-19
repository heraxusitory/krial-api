<?php


namespace App\Models\Catalog\DG;


use Illuminate\Database\Eloquent\Model;

class DGOptionGroup extends Model
{
    protected $table = 'dg_option_groups';

    protected $fillable = [
        'name',
        'code',
    ];

    public function options()
    {
        $this->hasMany(DGOption::class, 'dg_option_group_id', 'id');
    }
}
