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
}
