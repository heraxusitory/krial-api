<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\Benefit;
use League\Fractal\TransformerAbstract;

class BenefitTransformer extends TransformerAbstract
{
    public function transform(Benefit $benefit)
    {
        $attachments = $benefit->attachments;
        $media_urls = $attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });
        return [
            'id' => $benefit->id,
            'name' => $benefit->name,
            'code' => $benefit->code,
            'sort' => $benefit->sort,
            'is_active' => $benefit->is_active,
            'description' => $benefit->description,
            'media_urls' => $media_urls,
        ];
    }
}
