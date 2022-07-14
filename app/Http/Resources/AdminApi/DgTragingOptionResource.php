<?php


namespace App\Http\Resources\AdminApi;

use Illuminate\Http\Resources\Json\JsonResource;

class DgTragingOptionResource extends JsonResource
{
    public function toArray($request)
    {
        foreach ($this->attachments as $attachment) {
            $attachment->url = $attachment->getUrl();
        }
        return [
            'id' => $this->id,
            'price' => $this->price,
            'version' => $this->version,
            'attachments' => $this->attachments,
        ];
    }
}
