<?php


namespace App\Models\Catalog;


use Illuminate\Database\Eloquent\Model;

class CategoryToMarketingBanner extends Model
{
    protected $table = 'category_to_marketing_banner';

    protected $fillable = [
        'category_id',
        'marketing_banner_id',
    ];
}
