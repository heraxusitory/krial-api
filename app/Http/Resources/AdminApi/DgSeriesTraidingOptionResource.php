<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class DgSeriesTraidingOptionResource extends JsonResource
{
    public function toArray($request)
    {
        foreach ($this->attachments as $attachment) {
            $attachment->url = $attachment->getUrl();
        }
        return [
            'id' => $this->id,
            'version' => $this->version,
            'attachments' => $this->attachments,
        ];
    }
}
