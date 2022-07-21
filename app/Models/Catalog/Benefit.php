<?php


namespace App\Models\Catalog;


use App\Laravue\Models\Traits\Attachmentable;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use Attachmentable;

    protected $table = 'benefits';

    protected $fillable = [
        'name',
        'code',
        'sort',
        'description',
        'is_active',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_to_benefit');
    }
}
