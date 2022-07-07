<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DgEngineManufactureResource;
use App\Models\References\DGEngineManufacture;
use Illuminate\Http\Request;

class DgEngineManufactureController extends Controller
{
    public function index(Request $request)
    {
        return DgEngineManufactureResource::collection(DGEngineManufacture::all());
    }
}
