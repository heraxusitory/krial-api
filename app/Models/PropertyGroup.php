<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PropertyGroup extends Model
{
    protected $table = 'property_groups';

    protected $fillable = [
        'name',
    ];
}
