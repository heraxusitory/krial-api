<?php


namespace App\Http\Resources\AdminApi;


use Illuminate\Http\Resources\Json\JsonResource;

class DGSeriesResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'traiding_options' => [],
        ];

        if ($this->traiding_options->isNotEmpty())
            $data['traiding_options'] = DgSeriesTraidingOptionResource::collection($this->traiding_options);

        return $data;
    }
}
