<?php


namespace App\Models\References;


use Illuminate\Database\Eloquent\Model;

class DGVersion extends Model
{
    protected $table = 'dg_versions';

    protected $fillable = [
        'name',
        'default_media',
    ];

    protected const VERSION_OPEN = 'open';
    protected const VERSION_IN_CASE = 'in_case';
    protected const VERSION_IN_CASE_ON_CHASSIS = 'in_case_on_chassis';
    protected const VERSION_IN_CONTAINER = 'in_container';
    protected const VERSION_IN_CONTAINER_ON_CHASSIS = 'in_container_on_chassis';

    public static function VERSION_OPEN()
    {
        return self::VERSION_OPEN;
    }

    public static function VERSION_IN_CASE()
    {
        return self::VERSION_IN_CASE;
    }

    public static function VERSION_IN_CASE_ON_CHASSIS()
    {
        return self::VERSION_IN_CASE_ON_CHASSIS;
    }

    public static function VERSION_IN_CONTAINER()
    {
        return self::VERSION_IN_CONTAINER;
    }

    public static function VERSION_IN_CONTAINER_ON_CHASSIS()
    {
        return self::VERSION_IN_CONTAINER_ON_CHASSIS;
    }

    public static function VERSIONS()
    {
        return [
            self::VERSION_OPEN,
            self::VERSION_IN_CASE,
            self::VERSION_IN_CASE_ON_CHASSIS,
            self::VERSION_IN_CONTAINER,
            self::VERSION_IN_CONTAINER_ON_CHASSIS,
        ];
    }
}
