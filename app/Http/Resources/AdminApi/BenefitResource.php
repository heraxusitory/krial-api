<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class BenefitResource extends JsonResource
{
    public function toArray($request)
    {
        foreach ($this->attachments as $attachment) {
            $attachment->url = $attachment->getUrl();
        }
        $category_ids = $this->categories->pluck('id');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'sort' => $this->sort,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'image_urls' => $this->attachments,
            'category_ids' => $category_ids,
        ];
    }
}
