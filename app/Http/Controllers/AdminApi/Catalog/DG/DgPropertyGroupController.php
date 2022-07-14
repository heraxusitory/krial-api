<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\PropertyGroup;
use Illuminate\Http\Request;

class DgPropertyGroupController extends Controller
{
    public function index(Request $request)
    {
        return PropertyGroup::query()->paginate($request->limit);
    }
}
