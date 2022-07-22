<?php


namespace App\Http\Controllers\AdminApi\Catalog\Marketing;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminApi\MarketingBannerResource;
use App\Models\Catalog\Marketing\MarketingBanner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarketingBannerController extends Controller
{
    public function index(Request $request)
    {
        $marketing_banners = MarketingBanner::query()->paginate($request->limit);
        return MarketingBannerResource::collection($marketing_banners);
    }

    public function get(Request $request, $marketing_banner_id)
    {
        $marketing_banner = MarketingBanner::query()->findOrFail($marketing_banner_id);
        return MarketingBannerResource::make($marketing_banner);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'sort' => 'required|integer',
            'type' => ['required', Rule::in([MarketingBanner::FORM_TYPE(), MarketingBanner::INFORMATION_TYPE()])],
            'header' => 'required|string|max:225',
            'description' => 'required|string|max:255',
            'url' => 'nullable|required_if:type,' . MarketingBanner::INFORMATION_TYPE(),
            'button_text' => 'nullable|required_if:type,' . MarketingBanner::FORM_TYPE(),
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'required|integer',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'required|integer',
        ]);
        $banner = MarketingBanner::query()->create($validated);

        $banner->attachments()->sync($request->image_url_ids ?? []);
        $banner->categories()->sync($request->category_ids ?? []);

        return response('', 201);
    }

    public function update(Request $request, $marketing_banner_id)
    {
        $marketing_banner = MarketingBanner::query()->findOrFail($marketing_banner_id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'sort' => 'required|integer',
            'type' => ['required', Rule::in([MarketingBanner::FORM_TYPE(), MarketingBanner::INFORMATION_TYPE()])],
            'header' => 'required|string|max:225',
            'description' => 'required|string|max:255',
            'url' => 'nullable|required_if:type,' . MarketingBanner::INFORMATION_TYPE(),
            'button_text' => 'nullable|required_if:type,' . MarketingBanner::FORM_TYPE(),
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'required|integer',
            'image_url_ids' => 'nullable|array',
            'image_url_ids.*' => 'required|integer',
        ]);
        $marketing_banner->update($validated);

        $marketing_banner->attachments()->sync($request->image_url_ids ?? []);
        $marketing_banner->categories()->sync($request->category_ids ?? []);

        return response('');
    }

    public function delete(Request $request, $marketing_banner_id)
    {
        $marketing_banner = MarketingBanner::query()->findOrFail($marketing_banner_id);
        $marketing_banner->delete();
        return response('', 204);
    }

    public function getTypes(Request $request)
    {
        return MarketingBanner::HUMAN_READABLES_TYPES();
    }
}
