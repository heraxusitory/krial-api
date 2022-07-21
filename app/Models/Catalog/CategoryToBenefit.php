<?php


namespace App\Models\Catalog;


use Illuminate\Database\Eloquent\Model;

class CategoryToBenefit extends Model
{
    protected $table = 'category_to_benefit';

    protected $fillable = [
        'category_id',
        'benefit_id',
    ];
}
