<?php


namespace App\Http\Controllers\AdminApi\Catalog\DG;


use App\Http\Controllers\Controller;
use App\Models\Catalog\DG\DGProduct;
use Illuminate\Http\Request;

class DGTradingOptionController extends Controller
{
    public function index(Request $request, $dg_product_id)
    {
        $product = DGProduct::query()->findOrFail($dg_product_id);
        return $product->traiding_options()->with('version')->get();
    }
}
