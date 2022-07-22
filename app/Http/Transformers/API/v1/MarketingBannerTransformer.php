<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\Marketing\MarketingBanner;
use League\Fractal\TransformerAbstract;

class MarketingBannerTransformer extends TransformerAbstract
{
    public function transform(MarketingBanner $banner)
    {
        $attachments = $banner->attachments;
        $media_urls = $attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });

        $data = [
            'id' => $banner->id,
            'sort' => $banner->sort,
            'type' => $banner->type,
            'header' => $banner->header,
            'description' => $banner->description,
            'media_urls' => $media_urls,
        ];

        if ($banner->type === MarketingBanner::INFORMATION_TYPE())
            $data['url'] = $banner->url;
        elseif ($banner->type === MarketingBanner::FORM_TYPE())
            $data['button_text'] = $banner->button_text;

        return $data;
    }
}
