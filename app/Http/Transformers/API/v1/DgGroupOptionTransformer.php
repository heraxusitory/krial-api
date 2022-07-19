<?php


namespace App\Http\Transformers\API\v1;


use App\Models\Catalog\DG\DGOptionGroup;
use League\Fractal\TransformerAbstract;

class DgGroupOptionTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'options',
    ];

    public function transform(DGOptionGroup $option_group)
    {
        return [
            'id' => $option_group->id,
            'name' => $option_group->name,
            'code' => $option_group->code,
        ];
    }

    public function includeOptions(DGOptionGroup $option_group)
    {
        $options = $option_group->options;
        if (!is_null($options))
            return $this->collection($options, new DgOptionTransformer());
        return $this->null();
    }
}
