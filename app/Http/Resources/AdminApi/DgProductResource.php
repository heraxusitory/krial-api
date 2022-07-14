<?php


namespace App\Http\Resources\AdminApi;

use Illuminate\Http\Resources\Json\JsonResource;

class DgProductResource extends JsonResource
{
    public function toArray($request)
    {
//        dd($this);
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'second_name' => $this->second_name,
            'manufacture_name' => $this->manufacture?->name,
            'manufacture_id' => $this->manufacture?->id,
            'engine_manufacture_name' => $this->engine_manufacture?->name,
            'engine_manufacture_id' => $this->engine_manufacture?->id,
            'sort' => $this->sort,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'allgen_vendor_code' => $this->allgen_vendor_code,
            'allgen_url' => $this->allgen_url,
            'property_groups' => [],
            'traiding_options' => [],
        ];

//        dd($this->resource->propertyGroupsWithProperties());
        if ($this->resource->propertyGroupsWithProperties()) {
            $data['property_groups'] = DgPropertyGroupResource::collection($this->resource->propertyGroupsWithProperties());
        }
        if ($this->traiding_options) {
            $data['traiding_options'] = DgTragingOptionResource::collection($this->traiding_options);
        }

        return $data;
    }
}
