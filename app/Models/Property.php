<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Property extends Model
{
    use QueryCacheable;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'code',
        'is_main_in_card',
        'is_main_in_group',
        'is_main_in_header',
        'is_filterable',
        'filter_type',
        'property_group_id',
        'description',
    ];

    protected $casts = [
        'is_main_in_card' => 'boolean',
        'is_main_in_group' => 'boolean',
        'is_main_in_header' => 'boolean',
        'is_filterable' => 'boolean',
    ];

    private const FILTER_TYPE_LIST = 'list';
    private const FILTER_TYPE_INPUT = 'input';
    private const FILTER_TYPE_RANGE = 'range';

    private const HUMAN_READABLE_FILTER_TYPE_LIST = 'Список';
    private const HUMAN_READABLE_FILTER_TYPE_INPUT = 'Значение';
    private const HUMAN_READABLE_FILTER_TYPE_RANGE = 'Диапазон';

    public static function FILTER_TYPE_LIST()
    {
        return self::FILTER_TYPE_LIST;
    }

    public static function FILTER_TYPE_INPUT()
    {
        return self::FILTER_TYPE_INPUT;
    }

    public static function FILTER_TYPE_RANGE()
    {
        return self::FILTER_TYPE_RANGE;
    }

    public static function FILTERS()
    {
        return [
            self::FILTER_TYPE_INPUT,
            self::FILTER_TYPE_LIST,
            self::FILTER_TYPE_RANGE,
        ];
    }

    public static function HUMAN_READABLE_FILTERS()
    {
        return [
            self::FILTER_TYPE_INPUT => self::HUMAN_READABLE_FILTER_TYPE_INPUT,
            self::FILTER_TYPE_LIST => self::HUMAN_READABLE_FILTER_TYPE_LIST,
            self::FILTER_TYPE_RANGE => self::HUMAN_READABLE_FILTER_TYPE_RANGE,
        ];
    }

    public function value()
    {
        return $this;
    }

    public function group()
    {
        return $this->hasOne(PropertyGroup::class, 'id', 'property_group_id');
    }
}
