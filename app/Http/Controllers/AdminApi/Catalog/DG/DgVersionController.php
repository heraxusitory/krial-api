<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\References\DGVersion;
use Illuminate\Http\Request;

class DgVersionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['data' => DGVersion::all()]);
    }
}
