<?php


namespace App\Http\Controllers\API\v1\Catalog;


use App\Http\Controllers\Controller;
use App\Http\Serializers\CustomSerializer;
use App\Http\Transformers\API\v1\DgProductTransformer;
use App\Models\Catalog\DG\DGProduct;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\ArraySerializer;

class DgProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Spatie\Fractal\Fractal
     */
    /**
     * @OA\Get(
     *     path="/catalog/dg",
     *     summary="Получить список ДГУ",
     *     tags={"Дизельные генераторы"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/DgProduct")
     *         ),
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = DGProduct::query()->paginate();

        return fractal()->collection($products)
            ->parseIncludes(['properties'])
            ->transformWith(DgProductTransformer::class)
            ->paginateWith(new IlluminatePaginatorAdapter($products))
            ->serializeWith(ArraySerializer::class);
    }

    /**
     * @OA\Get(
     *     path="/catalog/dg/{dg_product_id}",
     *     summary="Получить элемент из списка ДГУ",
     *     tags={"Дизельные генераторы"},
     *      @OA\Parameter(
     *        name="dg_product_id",
     *        in="path",
     *        description="Уникальный идентификатор элемента списка ДГУ",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),  *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/DgProduct")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/DgProduct")
     *         ),
     *     )
     * )
     */
    public function get(Request $request, $dg_product_id)
    {
        $product = DGProduct::query()->with('properties')->findOrFail($dg_product_id);

        return fractal()->item($product)
            ->parseIncludes(['property_groups', 'traiding_options'])
            ->transformWith(DgProductTransformer::class)
            ->serializeWith(ArraySerializer::class);
    }
}
