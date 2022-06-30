<?php


namespace App\Http\Resources\AdminApi;

use Illuminate\Http\Resources\Json\JsonResource;

class DgProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'second_name' => $this->second_name,
            'sort' => $this->sort,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'allgen_vendor_code' => $this->allgen_vendor_code,
            'allgen_url' => $this->allgen_url,
        ];
    }
}
