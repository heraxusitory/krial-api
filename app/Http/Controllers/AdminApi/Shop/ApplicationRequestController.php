<?php


namespace App\Http\Controllers\AdminApi\Shop;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\Shop\ApplicationRequestResource;
use App\Models\Shop\ApplicationRequest;
use Illuminate\Http\Request;

class ApplicationRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ApplicationRequest::paginate($request->limit);
        return ApplicationRequestResource::collection($requests);
    }

    public function get(Request $request, $app_request_id)
    {
        $app_request = ApplicationRequest::query()->findOrFail($app_request_id);
        return ApplicationRequestResource::make($app_request);
    }

    public function update(Request $request, $app_request_id)
    {
        $app_request = ApplicationRequest::query()->findOrFail($app_request_id);
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'sort' => 'nullable|numeric',
        ]);

        $app_request->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'sort' => $request->sort ?? 100,
        ]);

        return response('');
    }
}
