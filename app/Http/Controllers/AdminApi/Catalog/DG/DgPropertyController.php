<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DgPropertyController extends Controller
{
    public function index(Request $request)
    {
        return Property::query()->with('group')->paginate($request->limit);
    }

    public function getFilterTypes(Request $request)
    {
        return Property::HUMAN_READABLE_FILTERS();
    }

    public function update(Request $request, $property_id)
    {
        $property = Property::query()->find($property_id);
        $request->validate([
            'is_main_in_group' => 'nullable|boolean',
            'is_main_in_header' => 'nullable|boolean',
            'is_main_in_card' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'filter_type' => ['nullable', Rule::in(Property::FILTERS())],
            'description' => 'nullable|string',
        ]);
        $data = array_filter($request->only('is_main_in_group', 'is_main_in_card', 'description', 'is_main_in_header', 'is_filterable', 'filter_type'), fn($item) => !is_null($item));
        if (!empty($data))
            $property->update($data);
        return response('');
    }
}
