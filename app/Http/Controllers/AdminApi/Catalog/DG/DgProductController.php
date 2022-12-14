<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DgProductResource;
use App\Http\Transformers\AdminAPI\v1\DgProductTransformer;
use App\Models\Catalog\DG\DGProduct;
use App\Models\Catalog\DG\DGTradingOption;
use App\Services\Sortings\DgProductSorting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class DgProductController extends Controller
{
    public function index(Request $request, DgProductSorting $sorting)
    {
        $sort = $request->get('sort');
        $sort_field = in_array($sort, [
            'id', 'name', ''
        ]);
        $query = DGProduct::query()
            ->with('series');
        $query = $request?->name ? $query->where('dg_products.name', 'like', "%{$request->name}%") : $query;
        $products = $query
            ->sorting($sorting)
            ->paginate($request->limit);
//        dd($query
//            ->toSql());
        return fractal()
            ->collection($products)
            ->transformWith(DgProductTransformer::class)
            ->paginateWith(new IlluminatePaginatorAdapter($products));
    }

    public function get(Request $request, $product_id)
    {
        $product = DGProduct::query()->with(['traiding_options.version', 'properties'])->findOrFail($product_id);
//        dd($product->properties->toArray());
//        foreach ($product->traiding_options as $traiding_option) {
//            foreach ($traiding_option->attachments as $attachment) {
//                $attachment->url = $attachment->getUrl();
//            }
//        }
        return DgProductResource::make($product);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_products,code',
            'manufacture_id' => 'required|exists:dg_manufactures,id',
            'engine_manufacture_id' => 'required|exists:dg_engine_manufactures,id',
            'is_active_second_name' => 'required|boolean',
            'second_name' => 'nullable|required_if:is_active_second_name,true|string',
            'is_active' => 'required|boolean',
            'internal_vendor_code' => 'nullable|string',
            'traiding_options.*' => 'nullable|array',
            'traiding_options.*.version.id' => 'required|exists:dg_versions,id',
            'traiding_options.*.price' => 'required|numeric',
            'traiding_options.*.attachment_ids' => 'nullable|array',
            'traiding_options.*.attachment_ids.*' => 'required|numeric',
        ]);


        DB::transaction(function () use ($request) {
            $dg_product = DGProduct::query()->create([
                'name' => $request->name,
                'code' => $request->code,
                'manufacture_id' => $request->manufacture_id,
                'engine_manufacture_id' => $request->engine_manufacture_id,
                'is_active_second_name' => $request->is_active_second_name,
                'second_name' => $request->second_name,
                'is_active' => $request->is_active,
                'internal_vendor_code' => $request->internal_vendor_code ?? null,
            ]);

            foreach ($request->traiding_options as $traiding_option) {
                $dg_traiding_option = $dg_product->traiding_options()->create([
                    'dg_version_id' => $traiding_option['version']['id'],
                    'price' => $traiding_option['price'],
                ]);
                $dg_traiding_option->attachments()->sync($traiding_option['attachment_ids'] ?? []);
            }
        });

        return response()->json('', 201);
    }

    public function update(Request $request, $product_id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_products,code,' . $product_id,
            'manufacture_id' => 'required|exists:dg_manufactures,id',
            'engine_manufacture_id' => 'required|exists:dg_engine_manufactures,id',
            'is_active_second_name' => 'required|boolean',
            'second_name' => 'nullable|required_if:is_active_second_name,true|string',
            'is_active' => 'required|boolean',
            'internal_vendor_code' => 'nullable|string|unique:dg_products,internal_vendor_code,' . $product_id,
            'traiding_options.*' => 'nullable|array',
            'traiding_options.*.version.id' => 'required|exists:dg_versions,id',
            'traiding_options.*.price' => 'required|numeric',
            'traiding_options.*.attachment_ids' => 'nullable|array',
            'traiding_options.*.attachment_ids.*' => 'required|numeric',
        ]);

        $dg_product = DGProduct::query()->findOrFail($product_id);

        DB::transaction(function () use ($dg_product, $request) {
            $dg_product->update([
                'name' => $request->name,
                'code' => $request->code,
                'manufacture_id' => $request->manufacture_id,
                'engine_manufacture_id' => $request->engine_manufacture_id,
                'is_active_second_name' => $request->is_active_second_name,
                'second_name' => $request->second_name,
                'is_active' => $request->is_active,
                'internal_vendor_code' => $request->internal_vendor_code,
            ]);

            $dg_option_ids = [];
            foreach ($request->traiding_options as $traiding_option) {
                $dg_traiding_option = $dg_product->traiding_options()->updateOrCreate(
                    [
                        'dg_version_id' => $traiding_option['version']['id'],
                    ],
                    [
                        'dg_version_id' => $traiding_option['version']['id'],
                        'price' => $traiding_option['price'],
                    ]);
                $dg_option_ids[] = $dg_traiding_option->id;
                $dg_traiding_option->attachments()->sync($traiding_option['attachment_ids'] ?? []);
            }
            $dg_product->traiding_options()->whereNotIn('id', $dg_option_ids)->delete();
        });
        return response('');
    }

    public function delete(Request $request, $product_id)
    {
        $product = DGProduct::query()->findOrFail($product_id);
        $product->delete();
        return response('', 204);
    }

    public function updateProductRow(Request $request, $dg_product_id)
    {
        $dg_product = DGProduct::query()->find($dg_product_id);
        $request->validate([
            'series_id' => 'nullable|exists:dg_series,id',
        ]);
        $data = $request->only('series_id');
        if (!empty($data))
            $dg_product->update($data);
        return response('');
    }

    public function changeActive(Request $request)
    {
        $request->validate([
            'dg_products' => 'required|array',
            'dg_products.*.id' => 'required|exists:dg_products,id',
            'is_active' => 'required|boolean',
        ]);

        $dg_product_ids = collect($request->dg_products)->pluck('id');

        DGProduct::query()->whereIn('id', $dg_product_ids)->update(['is_active' => $request->is_active]);

        return response('');
    }

    public function setSeries(Request $request)
    {
        $request->validate([
            'dg_products' => 'required|array',
            'dg_products.*.id' => 'required|exists:dg_products,id',
            'series_id' => 'nullable|exists:dg_series,id',
        ]);

        $dg_product_ids = collect($request->dg_products)->pluck('id');

        DGProduct::query()->whereIn('id', $dg_product_ids)->update(['series_id' => $request->series_id ?? null]);

        return response('');
    }
}
