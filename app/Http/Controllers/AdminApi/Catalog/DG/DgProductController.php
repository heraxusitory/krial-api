<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\DgProductResource;
use App\Models\Catalog\DG\DGProduct;
use Illuminate\Http\Request;

class DgProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DGProduct::query()->paginate($request->limit);
        return DgProductResource::collection($products);
    }
}
