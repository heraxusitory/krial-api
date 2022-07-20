<?php


namespace App\Http\Resources\AdminApi\Shop;


use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'sort' => $this->sort,
            'config' => $this->config,
        ];
    }
}
