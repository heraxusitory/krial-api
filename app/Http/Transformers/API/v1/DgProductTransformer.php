<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\DG\DGProduct;
use League\Fractal\TransformerAbstract;

class DgProductTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'manufacture', 'engineManufacture',
    ];

    protected array $availableIncludes = [
        'properties', 'traidingOptions',
    ];


    public function transform(DGProduct $product): array
    {
        $first_traiding_option = $product->traiding_options->where('dg_version_id', 1)->first();
        $attachments = $first_traiding_option->attachments;
        $media_urls = $attachments->map(function ($attachment) {
            return $attachment->getUrl();
        });
        return [
            'name' => $product->name,
            'code' => $product->code,
            'price' => $first_traiding_option->price,
            'media' => $media_urls
        ];
    }

    public function includeManufacture(DGProduct $product)
    {
        $manufacture = $product->manufacture;
        if (!is_null($manufacture))
            return $this->item($manufacture, new DgManufactureTransformer());
        return $this->null();
    }

    public function includeEngineManufacture(DGProduct $product)
    {
        $engine_manufacture = $product->engine_manufacture;
        if (!is_null($engine_manufacture))
            return $this->item($engine_manufacture, new DgEngineManufactureTransformer());
        return $this->null();
    }

    public function includeProperties(DGProduct $product)
    {
        $properties = $product->properties;
        if (!is_null($properties))
            return $this->collection($properties, new DgPropertyTransformer());
        return $this->null();
    }

    public function includeTraidingOptions(DGProduct $product)
    {
        $properties = $product->traiding_options;
        if (!is_null($properties))
            return $this->collection($properties, new DgPTraidingOptionTransformer());
        return $this->null();
    }
}
