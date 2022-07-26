<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DGSeriesResource;
use App\Models\Catalog\DG\DGSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DGSeriesController extends Controller
{
    public function index(Request $request)
    {
        $query = DGSeries::query();
        if ($request?->limit) {
            $dg_series = DGSeries::query()->paginate($request->limit);
        } else {
            $dg_series = $query->get();
        }
        return DGSeriesResource::collection($dg_series);
    }

    public function get(Request $request, $dg_series_id)
    {
        $dg_series = DGSeries::query()->findOrFail($dg_series_id);
        return DGSeriesResource::make($dg_series);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_series,code',
            'traiding_options' => 'nullable|array',
            'traiding_options.*.version.id' => 'required|exists:dg_versions,id',
            'traiding_options.*.attachment_ids' => 'nullable|array',
            'traiding_options.*.attachment_ids.*' => 'required|numeric',
        ]);

        $dg_series = DB::transaction(function () use ($request) {
            /** @var DGSeries $dg_series */
            $dg_series = DGSeries::query()->create([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            foreach ($request->traiding_options ?? [] as $traiding_option) {
                $to = $dg_series->traiding_options()->create([
                    'dg_version_id' => $traiding_option['version']['id'],
                ]);
                $to->attachments()->sync($traiding_option['attachment_ids'] ?? []);
            }
            return $dg_series;
        });
        return response('', 201);
    }

    public function update(Request $request, $dg_series_id)
    {
        /** @var DGSeries $dg_series */
        $dg_series = DGSeries::query()->findOrFail($dg_series_id);
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_series,code,' . $dg_series_id,
            'traiding_options' => 'nullable|array',
            'traiding_options.*.version.id' => 'required|exists:dg_versions,id',
            'traiding_options.*.attachment_ids' => 'nullable|array',
            'traiding_options.*.attachment_ids.*' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $dg_series) {
            $dg_series->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            $to_ids = [];
            foreach ($request->traiding_options ?? [] as $traiding_option) {
                $to = $dg_series->traiding_options()->updateOrCreate([
                    'dg_version_id' => $traiding_option['version']['id'],
                ]);
                $to_ids[] = $to->id;
                $to->attachments()->sync($traiding_option['attachment_ids'] ?? []);
                $dg_series->traiding_options()->whereNotIn('id', $to_ids)->delete();
            }
        });
        return response('');
    }

    public function delete(Request $request, $dg_series_id)
    {
        /** @var DGSeries $dg_series */
        $dg_series = DGSeries::query()->findOrFail($dg_series_id);
        DB::transaction(function () use ($request, $dg_series) {
            foreach ($dg_series->traiding_options as $traiding_option) {
                $traiding_option->attachments()->sync([]);
            }
            $dg_series->traiding_options()->delete();
            $dg_series->delete();
        });
        return response('', 204);
    }
}
