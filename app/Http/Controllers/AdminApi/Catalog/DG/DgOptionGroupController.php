<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\Catalog\DG\DGOptionGroup;
use Illuminate\Http\Request;

class DgOptionGroupController extends Controller
{
    public function index(Request $request)
    {
        return DGOptionGroup::query()->paginate($request->limit);
    }

    public function get(Request $request, $option_group_id)
    {
        $option_group = DGOptionGroup::query()->findOrFail($option_group_id);
        return response()->json(['data' => $option_group]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_option_groups,code',
        ]);

        DGOptionGroup::query()->create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response()->json('', 201);
    }

    public function update(Request $request, $option_group_id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:dg_option_groups,code,' . $option_group_id,
        ]);

        $option_group = DGOptionGroup::query()->findOrFail($option_group_id);


        $option_group->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response('');
    }

    public function delete(Request $request, $option_group_id)
    {
        $product = DGOptionGroup::query()->findOrFail($option_group_id);
        $product->delete();
        return response('', 204);
    }
}
