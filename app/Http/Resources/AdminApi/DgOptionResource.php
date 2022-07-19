<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class DgOptionResource extends JsonResource
{
    public function toArray($request)
    {
        foreach ($this->attachments as $attachment) {
            $attachment->url = $attachment->getUrl();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dg_option_group_id' => $this->dg_option_group_id,
            'description' => $this->description,
            'price' => $this->price,
            'group' => $this->group,
            'image_urls' => $this->attachments,
//            'image_url_ids' => $this->attachments->isNotEmpty() ? $this->attachments->pluck('id') : [],
        ];
    }
}
