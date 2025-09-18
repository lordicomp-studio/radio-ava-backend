<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Archive\ArchiveQuickSearchResource;
use App\Http\Resources\Api\ArticleWidgetResource;
use App\Http\Resources\Api\ProductWidgetResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArchiveController extends Controller
{

    private int $archiveResultCount = 12;
    private int $archiveQuickSearchResultCount = 5;

    public function search(Request $request){
        $fields = $request->validate([
            'type' => ['required', Rule::in(['Product', 'Article'])],
            'sort' => [ 'nullable', Rule::in(['latest', 'popular', 'priceDesc', 'priceAsc', 'mostViewed']) ],
            'query' => 'nullable', // if it is null then we show all (%% query)
            'cat_id' => 'nullable',
        ]);

        $dataQuery = [];
        $searchColumnName = $fields['type'] == 'Product' ? 'name' : 'title'; // Article doesn't have name. it has title.
        $searchText = $fields['query'] ?? '';

        if (isset($fields['cat_id'])){
            // find the given category
            $dataQuery = Category::find($fields['cat_id']);
            // get products or articles according to $fields['type']
            $dataQuery = $fields['type'] === 'Product' ? $dataQuery->products() : $dataQuery->articles();
            // apply name/title search
            $dataQuery = $dataQuery->where($searchColumnName, 'LIKE', '%' . $searchText . '%');
        }else{
            // define the right model namespace
            $model = "App\\Models\\" . $fields['type'];
            // apply name/title search
            $dataQuery = $model::where($searchColumnName, 'LIKE', '%' . $searchText . '%');
        }


        // apply sorting. it sorts by 'latest' by default.
        $dataQuery = $this->sortQuery($dataQuery, $fields['sort'] ?? 'latest', $fields['type']);

        // apply eager-loading and use the related resource
        if ($fields['type'] == 'Product'){
            $dataQuery = $dataQuery->with(['cover', 'categories'])
                ->withCount(['payments'])->withAvg('rates', 'rate');

            return ProductWidgetResource::collection(
                $dataQuery->where('status', ProductStatusEnum::Confirmed->value)->paginate($this->archiveResultCount)
            )->resource;
        }else{
            $dataQuery = $dataQuery->with(['photo', 'categories']);

            return ArticleWidgetResource::collection(
                $dataQuery->where('status', 1)->paginate($this->archiveResultCount)
            )->resource;
        }
    }

    public function category(Request $request, Category $category){
        return $this->getTagOrCategoryProductsOrArticles($request, $category);
    }

    public function tag(Request $request, Tag $tag){
        return $this->getTagOrCategoryProductsOrArticles($request, $tag);
    }

    private function getTagOrCategoryProductsOrArticles(Request $request, Category|Tag $model){
        $fields = $request->validate([
            'type' => ['required', Rule::in(['Product', 'Article'])],
            'sort' => [ 'nullable', Rule::in(['latest', 'popular', 'priceDesc', 'priceAsc', 'mostViewed']) ],
        ]);

        if ($fields['type'] === 'Product'){
            $dataQuery = $model->products()->with(['cover', 'categories'])->withCount(['payments', 'views']);
            $dataQuery = $this->sortQuery($dataQuery, $fields['sort'] ?? 'latest', $fields['type']);

            return ProductWidgetResource::collection(
                $dataQuery->where('status', ProductStatusEnum::Confirmed->value)->paginate($this->archiveResultCount)
            )->resource;
        }else{
            // here $fields['type'] is 'Article'
            $dataQuery = $model->articles()->with(['photo', 'categories'])->withCount('views');
            $dataQuery = $this->sortQuery($dataQuery, $fields['sort'] ?? 'latest', $fields['type']);

            return ArticleWidgetResource::collection(
                $dataQuery->where('status', 1)->paginate($this->archiveResultCount)
            )->resource;
        }
    }

    private function sortQuery($query, $sortBy, string $type){
        return
            match ($sortBy) {
                'latest' => $query->orderBy('published_at', 'DESC'), // it is done by default
                'popular' => $type === 'product'
                    ? $query->orderBy('payments_count', 'DESC')
                    : $query->withAvg('rates', 'rate')->orderBy('rates_avg_rate', 'DESC'),
                'priceDesc' => $query->orderBy('price', 'DESC'),
                'priceAsc' => $query->orderBy('price', 'ASC'),
                'mostViewed' => $query->withCount('views')->orderBy('views_count', 'DESC'),
//            default => $dataQuery->orderBy('published_at', 'DESC'),
            };
    }

    public function quickSearch(Request $request){
        $fields = $request->validate([
            'query' => 'required',
        ]);

        $data = Product::where('name', 'LIKE', '%' . $fields['query'] . '%')
            ->where('status', ProductStatusEnum::Confirmed->value)
            ->withAvg('rates', 'rate')
            ->withCount('payments')
            ->with(['cover'])
            ->latest()->take($this->archiveQuickSearchResultCount)->get();

        return ArchiveQuickSearchResource::collection($data)->resource;
    }

}
