<?php


namespace App\Http\Transformers\API\v1;


use App\Models\PropertyGroup;
use League\Fractal\TransformerAbstract;

class DgPropertyGroupTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'properties', 'main_in_group_properties',
    ];

    protected array $availableIncludes = [
    ];

    public function transform(PropertyGroup $group)
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
        ];
    }

    public function includeProperties(PropertyGroup $group)
    {
        $properties = $group->properties;
        if (!is_null($properties))
            return $this->collection($properties, new DgPropertyTransformer());
        return $this->null();
    }

    public function includeMainInGroupProperties(PropertyGroup $group)
    {
        $properties = $group->properties->filter(fn($prop) => $prop->is_main_in_group);
        if (!is_null($properties))
            return $this->collection($properties, new DgPropertyTransformer());
        return $this->null();
    }
}
