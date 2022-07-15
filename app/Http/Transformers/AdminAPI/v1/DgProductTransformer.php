<?php


namespace App\Http\Transformers\AdminAPI\v1;


use App\Models\Catalog\DG\DGProduct;
use League\Fractal\TransformerAbstract;

class DgProductTransformer extends TransformerAbstract
{
    public function transform(DGProduct $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'second_name' => $product->second_name,
            'manufacture_name' => $product->manufacture?->name,
            'manufacture_id' => $product->manufacture?->id,
            'engine_manufacture_name' => $product->engine_manufacture?->name,
            'engine_manufacture_id' => $product->engine_manufacture?->id,
            'sort' => $product->sort,
            'is_active' => $product->is_active,
            'description' => $product->description,
            'allgen_vendor_code' => $product->allgen_vendor_code,
            'allgen_url' => $product->allgen_url,
        ];
    }
}
