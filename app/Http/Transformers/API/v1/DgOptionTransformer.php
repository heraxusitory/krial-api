<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\DG\DGOption;
use League\Fractal\TransformerAbstract;

class DgOptionTransformer extends TransformerAbstract
{
    public function transform(DGOption $option)
    {
        $media_urls = $option->attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });
        return [
            'id' => $option->id,
            'name' => $option->name,
            'description' => $option->description,
            'price' => $option->price,
            'media_urls' => $media_urls,
        ];
    }
}
