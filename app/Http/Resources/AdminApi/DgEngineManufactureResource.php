<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class DgEngineManufactureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'country' => $this->country,
            'description' => $this->description,
        ];
    }
}
