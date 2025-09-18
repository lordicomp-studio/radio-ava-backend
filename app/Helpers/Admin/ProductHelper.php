<?php

namespace App\Helpers\Admin;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductEditRequest;
use App\Models\Tag;

class ProductHelper
{
    public static function getCategories(string $searchText, int $categoryCount){
        return Category::where('name', 'LIKE', "%$searchText%")->take($categoryCount)->latest()->get();
    }

    public static function getTags(string $searchText, int $tagCount){
        return Tag::where('name', 'LIKE', "%$searchText%")->take($tagCount)->latest()->get();
    }

    public static function findOrCreateTags(array $tags): array{
        $tagIds = [];
        foreach ($tags as $tag){
            $tagModel = Tag::firstOrCreate(
                [ 'name' => $tag ],
                [ 'user_id' => auth()->id() ]
            );

            $tagIds[] = $tagModel->id;
        }

        return $tagIds;
    }

    public static function findOrCreateFeatures(array $features){
        $featureIds = [];
        foreach ($features as $feature){
            $featureModel = Feature::firstOrCreate(
                [ 'title' => $feature['title'], 'description' => $feature['description'] ],
                [ 'creator_id' => auth()->id() ]
            );

            array_push($featureIds, $featureModel->id);
            $featureIds[$featureModel->id] = [ 'price' => $feature['pivot']['price'] ];
        }

        return $featureIds;
    }

    public static function attachSuggestedTagsAndFeatures(Product | ProductEditRequest $product){
        $suggestedTags = $product->seller_suggested_info->tags ?? [];
        foreach ($suggestedTags as $tag){
            $tag->isSuggested = true;
            $product->tags[] = $tag;
        }

        $suggestedFeatures = $product->seller_suggested_info->features ?? [];
        foreach ($suggestedFeatures as $feature){
            $product->features[] = [
                'title' => $feature->title,
                'description' => $feature->description,
                'pivot' => [ 'price' => $feature->price ],
                'isSuggested' => true,
            ];
        }

        return $product;
    }
}
