<?php


namespace App\Services\Sortings;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QuerySorting
{
    protected Builder $builder;
    protected string $order;

    public function __construct(protected Request $request)
    {
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        $this->order = $this->sort_order();
        $method = Str::snake($this->sort_field());
        if (method_exists($this, $method)) {
            call_user_func([$this, $method]);
        } else $this->default_order();
    }

    protected function sort_field()
    {
        return data_get($this->request, 'sort');
    }

    protected function sort_order()
    {
        $sort_order_value = Str::camel(data_get($this->request, 'order'));
        return in_array($sort_order_value, ['asc', 'desc']) ? $sort_order_value : 'desc';
    }

    protected abstract function default_order();
}
