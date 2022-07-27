<?php


namespace App\Services\Sortings;


use Illuminate\Support\Facades\DB;

class DgProductSorting extends QuerySorting
{
    protected function default_order()
    {
        return $this->builder->orderByDesc('id');
    }

    public function id()
    {
        return $this->builder->orderBy('id', $this->order);
    }

    public function name()
    {
        return $this->builder->orderBy('name', $this->order);
    }

    public function manufacture_name()
    {
        return $this->builder
            ->select(['dg_products.*'])
            ->leftJoin('dg_manufactures', 'dg_products.manufacture_id', '=', 'dg_manufactures.id')
            ->orderBy('dg_manufactures.name', $this->order);
    }

    public function engine_manufacture_name()
    {
        return $this->builder
            ->select(['dg_products.*'])
            ->leftJoin('dg_engine_manufactures', 'dg_products.engine_manufacture_id', '=', 'dg_engine_manufactures.id')
            ->orderBy('dg_engine_manufactures.name', $this->order);
    }

    public function series_id()
    {
        return $this->builder
            ->select(['dg_products.*'])
            ->leftJoin('dg_series', 'dg_products.series_id', '=', 'dg_series.id')
            ->orderBy('dg_series.name', $this->order);
    }

    public function title()
    {
        return $this->builder->orderBy('second_name', $this->order);
    }

    public function code()
    {
        return $this->builder->orderBy('code', $this->order);
    }

    public function sort()
    {
        return $this->builder->orderBy('sort', $this->order);
    }

    public function is_active()
    {
        return $this->builder->orderBy('is_active', $this->order);
    }

    public function allgen_vendor_code()
    {
        return $this->builder->orderBy('allgen_vendor_code', $this->order);
    }
}
