<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DgManufactureResource;
use App\Models\References\DGManufacture;
use Illuminate\Http\Request;

class DgManufactureController extends Controller
{
    public function index(Request $request)
    {
        return DgManufactureResource::collection(DGManufacture::query()->get());
    }
}
