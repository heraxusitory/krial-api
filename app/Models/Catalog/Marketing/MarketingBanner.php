<?php


namespace App\Models\Catalog\Marketing;


use App\Laravue\Models\Traits\Attachmentable;
use App\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Model;

class MarketingBanner extends Model
{
    use Attachmentable;

    protected $table = 'marketing_banners';

    protected $fillable = [
        'is_active',
        'sort',
        'type',
        'header',
        'description',
        'url',
    ];

    private const INFORMATION_TYPE = 'information';
    private const FORM_TYPE = 'form';

    private const HUMAN_READABLE_INFORMATION_TYPE = 'Информационный';
    private const HUMAN_READABLE_FORM_TYPE = 'Заявка';

    public static function INFORMATION_TYPE()
    {
        return self::INFORMATION_TYPE;
    }

    public static function FORM_TYPE()
    {
        return self::FORM_TYPE;
    }

    public static function HUMAN_READABLES_TYPES()
    {
        return [
            self::INFORMATION_TYPE => self::HUMAN_READABLE_INFORMATION_TYPE,
            self::FORM_TYPE => self::HUMAN_READABLE_FORM_TYPE,
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_to_marketing_banner');
    }
}
