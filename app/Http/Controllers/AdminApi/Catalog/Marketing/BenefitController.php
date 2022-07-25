<?php


namespace App\Http\Controllers\AdminApi\Catalog\Marketing;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\BenefitResource;
use App\Models\Catalog\Benefit;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    public function index(Request $request)
    {
        $benefits = Benefit::query()->paginate($request->limit);
        return BenefitResource::collection($benefits);
    }

    public function get(Request $request, $benefit_id)
    {
        $benefit = Benefit::query()->findOrFail($benefit_id);
        return BenefitResource::make($benefit);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:benefits,code',
            'sort' => 'required|integer',
            'is_active' => 'required|boolean',
            'description' => 'required|string',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'required|integer',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'required|integer',
        ]);

        $benefit = Benefit::query()->create([
            'name' => $request->name,
            'code' => $request->code,
            'sort' => $request->sort ?? 100,
            'is_active' => $request->is_active,
            'description' => $request->description,
        ]);

        $benefit->categories()->sync($request->category_ids ?? []);
        $benefit->attachments()->sync($request->image_url_ids ?? []);

        return response('', 201);
    }

    public function update(Request $request, $benefit_id)
    {
        $benefit = Benefit::query()->findOrFail($benefit_id);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:benefits,code,' . $benefit_id,
            'sort' => 'required|integer',
            'is_active' => 'required|boolean',
            'description' => 'required|string',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'required|integer',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'required|integer',
        ]);

        $benefit->update([
            'name' => $request->name,
            'code' => $request->code,
            'sort' => $request->sort ?? 100,
            'is_active' => $request->is_active,
            'description' => $request->description,
        ]);

        $benefit->categories()->sync($request->category_ids ?? []);
        $benefit->attachments()->sync($request->image_url_ids ?? []);

        return response('');
    }

    public function delete(Request $request, $benefit_id)
    {
        $benefit = Benefit::query()->findOrFail($benefit_id);
        $benefit->delete();

        return response('', 204);
    }
}
