<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DgOptionResource;
use App\Models\Catalog\DG\DGOption;
use App\Models\Catalog\DG\DGOptionGroup;
use Illuminate\Http\Request;

class DgOptionController extends Controller
{
    public function index(Request $request)
    {
        $options = DGOption::query()->with(['group', 'attachments'])->paginate($request->limit);
        return DgOptionResource::collection($options);
    }

    public function get(Request $request, $dg_option_id)
    {
        $option = DGOption::query()->with(['group', 'attachments'])->findOrFail($dg_option_id);
        return DgOptionResource::make($option);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'dg_option_group_id' => 'required|exists:dg_option_groups,id',
            'price' => 'required|numeric',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'integer',
        ]);

        $dg_option_group = DGOptionGroup::query()->findOrFail($request->dg_option_group_id);

//        dd($dg_option_group->options);
        $dg_option = $dg_option_group->options()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        $dg_option->attachments()->sync($request->image_url_ids ?? []);

        return response()->json('', 201);
    }

    public function update(Request $request, $dg_option_id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'dg_option_group_id' => 'required|exists:dg_option_groups,id',
            'price' => 'required|numeric',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'integer',
        ]);

        /** @var DGOption $dg_option */
        $dg_option = DGOption::query()->findOrFail($dg_option_id);

//        dd($dg_option_group->options);
        $dg_option->update([
            'name' => $request->name,
            'dg_option_group_id' => $request->dg_option_group_id,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        $dg_option->attachments()->sync($request->image_url_ids ?? []);

        return response()->json('');
    }


    public function delete(Request $request, $dg_option_id)
    {
        $product = DGOption::query()->findOrFail($dg_option_id);
        $product->delete();
        return response('', 204);
    }
}
