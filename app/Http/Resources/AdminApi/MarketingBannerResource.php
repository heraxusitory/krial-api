<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class MarketingBannerResource extends JsonResource
{
    public function toArray($request)
    {
        foreach ($this->attachments as $attachment) {
            $attachment->url = $attachment->getUrl();
        }
        $category_ids = $this->categories->pluck('id');
        return [
            'id' => $this->id,
            'is_active' => $this->is_active,
            'sort' => $this->sort,
            'type' => $this->type,
            'header' => $this->header,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'url' => $this->url,
            'image_urls' => $this->attachments,
            'category_ids' => $category_ids,
        ];
    }
}
