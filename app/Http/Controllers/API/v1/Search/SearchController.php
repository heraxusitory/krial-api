<?php


namespace App\Http\Controllers\API\v1\Search;


use App\Http\Controllers\Controller;
use App\Http\Transformers\API\v1\DgProductTransformer;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\ArraySerializer;

class SearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/search",
     *     summary="Глобальный поиск через ElasticSearch",
     *     tags={"Поиск"},
     *      @OA\Parameter(
     *        name="query_str",
     *        in="query",
     *        description="Строка поиска",
     *        required=false,
     *        example="Дизельный",
     *        @OA\Schema(
     *           type="string",
     *        ),
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=503,
     *         description="Server unavailable"
     *     )
     * )
     */
    public function search(Request $request, SearchService $searchService)
    {
//        try {
//            $params = [
//                'filter_among' => array_map(function($string) {
//                    if (is_string($string)) return trim($string);
//                    else return null;
//                }, explode(',', $request->get('filter_among') ?? ""))
//            ];
        $collection = $searchService->search($request->get('query_str') ?? "");
//        return response()->json($collection = $searchService->search($request->get('query_str') ?? ""));
//        return response()->json($collection;
        if (array_key_exists('products', $collection->toArray())) {
            $collection['products'] =  fractal()->collection($collection['products'])
                ->parseIncludes(['main_card_properties', 'header_properties'])
                ->transformWith(DgProductTransformer::class)
//                ->paginateWith(new IlluminatePaginatorAdapter($products))
                ->serializeWith(ArraySerializer::class);
        }
        return response()->json($collection);
//        } catch (\Exception $e) {
//            Log::debug($e->getMessage(), $e->getTrace());
//            return response()->json(['message' => 'Service unavailable'], 503);
//        }
    }
}
