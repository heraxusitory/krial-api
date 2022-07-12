<?php


namespace App\Http\Transformers\API\v1;


use App\Models\References\DGEngineManufacture;
use League\Fractal\TransformerAbstract;

class DgEngineManufactureTransformer extends TransformerAbstract
{
    public function transform(DGEngineManufacture $engine_manufacture)
    {
        return [
            'id' => $engine_manufacture->id,
            'name' => $engine_manufacture->name,
            'code' => $engine_manufacture->code,
            'country' => $engine_manufacture->country,
        ];
    }
}
