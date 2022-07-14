<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class DgPropertyController extends Controller
{
    public function index(Request $request)
    {
        return Property::query()->with('group')->paginate($request->limit);
    }
}
