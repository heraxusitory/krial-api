<?php


namespace App\Http\Controllers\AdminApi\Catalog;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\CategoryResource;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::paginate($request->limit);
        return CategoryResource::collection($categories);
    }

    public function get(Request $request, $category_id)
    {
        $category = Category::query()->findOrFail($category_id);
        return CategoryResource::make($category);
    }

    public function update(Request $request, $category_id)
    {
        $category = Category::query()->findOrFail($category_id);
        $request->validate([
            'name' => 'required|string',
            'sort' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'required|integer',

            'compilations' => 'nullable|array',
            'compilations.*.name' => 'required|string',
            'compilations.*.url' => 'required|string',

            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'seo_h1' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'sort' => $request->sort ?? 100,
            'is_active' => $request->is_active,
            'seo_title' => $request->seo_title ?? null,
            'seo_description' => $request->seo_description ?? null,
            'seo_h1' => $request->seo_h1 ?? null,
            'compilations' => json_encode($request->compilations)
        ]);

        $category->attachments()->sync($request->image_url_ids ?? []);
        return response('');
    }
}
