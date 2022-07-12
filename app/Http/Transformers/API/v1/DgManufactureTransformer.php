<?php


namespace App\Http\Transformers\API\v1;


use App\Models\References\DGManufacture;
use League\Fractal\TransformerAbstract;

class DgManufactureTransformer extends TransformerAbstract
{
    public function transform(DGManufacture $manufacture)
    {
        return [
            'id' => $manufacture->id,
            'name' => $manufacture->name,
            'code' => $manufacture->code,
            'country' => $manufacture->country,
        ];
    }
}
