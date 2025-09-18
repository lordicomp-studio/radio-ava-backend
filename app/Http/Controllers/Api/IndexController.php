<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductStatusEnum;
use App\Helpers\DBHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ArticleWidgetResource;
use App\Http\Resources\Api\ProductWidgetResource;
use App\Models\Article;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

class IndexController extends Controller
{
    public function indexPage(){
        return response([
            'latestArticles' => $this->getLatestArticles(),
            'latestProducts' => $this->getLatestProducts(),
            'bestsellingDev' => $this->getBestSellingDev(),
            'featuredProducts' => $this->getFeaturedProducts(),
            'promotedProduct' => $this->getPromotedProducts(),
        ], 200);
    }

    private function getLatestArticles(){
        return ArticleWidgetResource::collection(
            Article::latest()->where('status', 1)
                ->with(['photo', 'categories'])
                ->withCount(['views'])
                ->take(9)->get()
        )->resource;
    }

    private function getLatestProducts(){
        return ProductWidgetResource::collection(
            Product::latest()->where('status', ProductStatusEnum::Confirmed->value)
                ->with(['cover', 'categories'])
                ->withCount(['payments', 'views'])
                ->withAvg('rates', 'rate')
                ->take(9)->get()
        )->resource;
    }

    private function getBestSellingDev(){
        $highestSellingUserData = DBHelper::findHighestSellingUserInfo();

        if (!$highestSellingUserData)
            return null;

        $userAvgProductRate = DBHelper::findUserAvgProductRate($highestSellingUserData->id);

        $user = User::where('id', $highestSellingUserData->id)
            ->with('profilePhoto')->withCount('products')->first();

        $products = DBHelper::findHighestSellingUserProducts($user->id, 3);

        return [
            'user' => [
                'id' => $user->id,
                'username' => $user->name,
                'fullname' => "$user->first_name $user->last_name",
                'avatarUrl' => $user->profilePhoto?->url,
            ],
            'salesCount' => $highestSellingUserData->sales_count,
            'productsCount' => $user->products_count,
            'satisfactionPercent' => round($userAvgProductRate * 20, 2), // $num * 20 is short for "  $num * 100 / 5"
            'products' => ProductWidgetResource::collection($products)->resource,
        ];
    }

    private function getFeaturedProducts(){
        $featuredProductIds = json_decode(Setting::where('key', 'theme_featured_products_ids')->first()?->value);

        if (!$featuredProductIds)
            return null;

        return ProductWidgetResource::collection(
            Product::latest()->whereIn('id', $featuredProductIds)
                ->with(['cover', 'categories'])
                ->withCount(['payments', 'views'])
                ->withAvg('rates', 'rate')
                ->take(9)->get()
        )->resource;
    }

    private function getPromotedProducts(){
        $promotedProductId = Setting::where('key', 'theme_promoted_product_id')->first()?->value;

        if (!$promotedProductId)
            return null;

        return new ProductWidgetResource(
            Product::where('id', $promotedProductId)
                ->with(['cover', 'categories'])
                ->withCount(['payments', 'views', 'rates'])
                ->withAvg('rates', 'rate')
                ->first()
        );
    }

}
