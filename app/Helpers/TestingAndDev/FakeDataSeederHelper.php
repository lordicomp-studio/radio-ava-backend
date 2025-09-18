<?php

namespace App\Helpers\TestingAndDev;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class FakeDataSeederHelper
{
    public static function makeRoundCentForPrice():string{
        $numbers = ['00', '25', '50', '75', '99'];
        return $numbers[rand(1, count($numbers)) - 1];
    }

    public static function addCategoryToAllCatables(string $namespace){
        $catables = $namespace::all();

        $insertableData = [];
        foreach ($catables as $catable){
            $insertableData[] = [
                'category_id' => Category::inRandomOrder()->first()?->id,
                'catable_id' => $catable->id,
                'catable_type' => $namespace,
            ];
        }

        DB::table('catables')->insert($insertableData);
    }

    public static function addTagToAllCatables(string $namespace){
        $taggables = $namespace::all();

        $insertableData = [];
        foreach ($taggables as $taggable){
            $insertableData[] = [
                'tag_id' => Tag::inRandomOrder()->first()?->id,
                'taggable_id' => $taggable->id,
                'taggable_type' => $namespace,
            ];
        }

        DB::table('taggables')->insert($insertableData);
    }


}
