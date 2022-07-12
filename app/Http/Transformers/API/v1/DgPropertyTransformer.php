<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Property;
use League\Fractal\TransformerAbstract;

class DgPropertyTransformer extends TransformerAbstract
{
    public function transform(Property $property)
    {
        return [
            'name' => $property->name,
            'code' => $property->code,
            'property_group' => $property->group,
            'description' => $property->description,
            'value' => $property->pivot->value,
        ];
    }
}
