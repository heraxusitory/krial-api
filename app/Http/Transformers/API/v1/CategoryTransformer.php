<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
    ];
    protected array $availableIncludes = [
        'benefits',
    ];

    public function transform(Category $category)
    {
        $attachments = $category->attachments;
        $media_urls = $attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });

        return [
            'id' => $category->id,
            'name' => $category->name,
            'code' => $category->code,
            'url' => $category->null,
            'is_root' => $category->is_root,
            'is_active' => $category->is_active,
            'sort' => $category->sort,
            'seo' => [
                'title' => $category->seo_title,
                'description' => $category->seo_description,
                'h1' => $category->seo_h1,
            ],
            'compilations' => json_decode($category->compilations) ?? [],
            'media_urls' => $media_urls,
        ];
    }

    public function includeBenefits(Category $category)
    {
        $benefits = $category->benefits;
        if (!is_null($benefits))
            return $this->collection($benefits, new BenefitTransformer());
        return $this->null();
    }
}
