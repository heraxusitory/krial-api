<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'compilations' => $this->compilations?? [],
            'is_active' => $this->is_active,
            'is_root' => $this->is_root,
            'seo_description' => $this->seo_description,
            'seo_h1' => $this->seo_h1,
            'seo_title' => $this->seo_title,
            'sort' => $this->sort,
            'categoriable_type' => $this->categoriable_type,
        ];
    }
}
