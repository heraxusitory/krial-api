<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\Catalog\DG\DGOption;
use Illuminate\Http\Request;

class DgOptionController extends Controller
{
    public function index(Request $request)
    {
        return DGOption::query()->with('group')->paginate($request->limit);

    }
}
