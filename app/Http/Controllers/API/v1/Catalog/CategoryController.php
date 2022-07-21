<?php


namespace App\Http\Controllers\API\v1\Catalog;


use App\Http\Controllers\Controller;
use App\Http\Transformers\API\v1\CategoryTransformer;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/catalog/categories",
     *     summary="Получить список категорий с преимуществами",
     *     tags={"Категории"},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $categories = Category::query()->where('is_active', true)->get();
        return fractal()
            ->collection($categories)
            ->parseIncludes(['benefits',])
            ->transformWith(CategoryTransformer::class)
            ->serializeWith(ArraySerializer::class);
    }

//    public function getById(Request $request, $category_id)
//    {
//        $category = Category::query()->where(['is_active' => true, 'is_root' => false])->findOrFail($category_id);
//        return fractal()
//            ->item($category)
//            ->parseIncludes(['benefits',])
//            ->transformWith(CategoryTransformer::class)
//            ->serializeWith(ArraySerializer::class);
//    }


    /**
     * @OA\Get(
     *     path="/catalog/categories/{category_code}",
     *     summary="Получить категорию по ее коду с преимуществами",
     *     tags={"Категории"},
     *      @OA\Parameter(
     *        name="category_code",
     *        in="path",
     *        description="Уникальный код категории",
     *        @OA\Schema(
     *           type="string",
     *        ),
     *        required=true,
     *        example="dg"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function getByCode(Request $request, $category_code)
    {
        $category = Category::query()
            ->where(['code' => $category_code, 'is_active' => true, 'is_root' => false])
            ->firstOrFail();
        return fractal()
            ->item($category)
            ->parseIncludes(['benefits',])
            ->transformWith(CategoryTransformer::class)
            ->serializeWith(ArraySerializer::class);
    }
}
