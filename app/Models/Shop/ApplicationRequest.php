<?php


namespace App\Models\Shop;


use Illuminate\Database\Eloquent\Model;

class ApplicationRequest extends Model
{
    protected $table = 'application_requests';

    protected $fillable = [
        'name',
        'email',
        'sort',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];
}
