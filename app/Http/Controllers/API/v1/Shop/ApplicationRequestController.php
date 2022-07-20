<?php


namespace App\Http\Controllers\API\v1\Shop;


use App\Http\Controllers\Controller;
use App\Models\Shop\ApplicationRequest;
use Illuminate\Http\Request;

class ApplicationRequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/shop/application_requests",
     *     summary="Создание заявки для коммерческого предложения",
     *     tags={"Заявки"},
     *     operationId="create",
     *    @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 example={"name": "Jessica Smith", "email": "email@email.ru", "phone": "+843774367"}
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json"
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json"
     *             )
     *         }
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        ApplicationRequest::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response('', 201);
    }
}
