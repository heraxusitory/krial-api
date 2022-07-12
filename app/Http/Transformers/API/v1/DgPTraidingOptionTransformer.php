<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\DG\DGTradingOption;
use League\Fractal\TransformerAbstract;

class DgPTraidingOptionTransformer extends TransformerAbstract
{
    public function transform(DGTradingOption $option)
    {
        $attachment_urls = $option->attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });
        return [
            'price' => $option->price,
            'version' => [
                'name' => $option->version->name,
                'code' => $option->version->code,
            ],
            'attachments' => $attachment_urls,
        ];
    }
}
