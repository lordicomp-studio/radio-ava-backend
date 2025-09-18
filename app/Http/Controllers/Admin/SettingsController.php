<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Submenu;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Psy\Util\Json;

class SettingsController extends Controller
{
    public function general(){
        $settings = collect(
            Setting::whereIn('key', ['title', 'description'])->get(['key', 'value'])
        );
        $settings->push(['key' => 'appDebug', 'value' => env('APP_DEBUG')]);


        $settingsMedia = DB::table('settings')
            ->whereIn('key', ['logo_id', 'favicon_id'])
            ->join('media', 'settings.value', '=', 'media.id')
            ->get();

        $settings = array_merge($settings->toArray(), $settingsMedia->toArray());

        return Inertia::render('Admin/Settings/General', compact('settings'));
    }

    public function seo(){
        $generalSettings = collect(
            Setting::whereIn('key', ['title', 'description'])->get(['key', 'value'])
        );

        $settings = collect(
            Setting::whereIn('key', ['meta_title', 'meta_description', 'ogp_title', 'ogp_description'])->get(['key', 'value'])
        );

        $settingsMedia = DB::table('settings')
            ->where('key', 'ogp_picture_id')
            ->join('media', 'settings.value', '=', 'media.id')
            ->get();

        $settings = array_merge($settings->toArray(), $settingsMedia->toArray());

        return Inertia::render('Admin/Settings/Seo', compact('generalSettings', 'settings'));
    }

    public function setGeneralSettings(Request $request){
        $validatedData = $request->validate([
            'title' => 'nullable | max:256',
            'description' => 'nullable | max:1024',
            'appDebug' => 'required | boolean',
            'logo_id' => 'nullable | integer',
            'favicon_id' => 'nullable | integer',
        ]);

        // updates if the key exists and creates if it doesn't
        Setting::upsert([
            ['key' => 'title', 'value' => $validatedData['title'] ?? ''],
            ['key' => 'description', 'value' => $validatedData['description'] ?? ''],
            ['key' => 'logo_id', 'value' => $validatedData['logo_id'] ?? ''],
            ['key' => 'favicon_id', 'value' => $validatedData['favicon_id'] ?? ''],
        ], ['key']);

        if (env('APP_DEBUG') !== $validatedData['appDebug']){
            $this->envUpdate('APP_DEBUG', $validatedData['appDebug']);
        }

        return response(null, 200);
    }

    public function setSeoSettings(Request $request){
        $validatedData = $request->validate([
            'metaTitle' => 'nullable | max:256',
            'metaDescription' => 'nullable | max:1024',
            'ogpTitle' => 'nullable | max:256',
            'ogpDescription' => 'nullable | max:1024',
            'ogp_picture_id' => 'nullable | integer',
        ]);

        // updates if the key exists and creates if it doesn't
        Setting::upsert([
            ['key' => 'meta_title', 'value' => $validatedData['metaTitle'] ?? ''],
            ['key' => 'meta_description', 'value' => $validatedData['metaDescription'] ?? ''],
            ['key' => 'ogp_title', 'value' => $validatedData['ogpTitle'] ?? ''],
            ['key' => 'ogp_description', 'value' => $validatedData['ogpDescription'] ?? ''],
            ['key' => 'ogp_picture_id', 'value' => $validatedData['ogp_picture_id'] ?? ''],
        ], ['key']);

        return response(null, 200);
    }

    public function theme(){
        $settings = collect(
            Setting::whereIn('key', ['theme_promoted_product_id', 'theme_featured_products_ids'])->get(['key', 'value'])
        );

        $promotedProduct = Product::find(
            $settings->where('key', 'theme_promoted_product_id')->first()?->value
        );
        $featuredProducts = Product::whereIn(
            'id', json_decode($settings->where('key', 'theme_featured_products_ids')->first()?->value) ?? []
        )->get();


        return Inertia::render('Admin/Settings/Theme', [
            'settings' => [
                'promotedProduct' => $promotedProduct,
                'featuredProducts' => $featuredProducts,
            ]
        ]);
    }

    public function setThemeSettings(Request $request){
        $validatedData = $request->validate([
            'theme_promoted_product_id' => 'nullable | int',
            'theme_featured_products_ids' => 'nullable | array',
        ]);

        // updates if the key exists and creates if it doesn't
        Setting::upsert([
            ['key' => 'theme_promoted_product_id',
                'value' => $validatedData['theme_promoted_product_id'] ?? ''],
            ['key' => 'theme_featured_products_ids',
                'value' => json_encode($validatedData['theme_featured_products_ids'] ?? '')],
        ], ['key']);

        return response(null, 200);
    }

    public function setFileSettings(Request $request){
        $validatedData = $request->validate([
            'file_acceptedFormats' => 'nullable',
            'file_uploadLimit' => 'nullable | integer',
            'file_chosenFoldering' => 'nullable',
        ]);

        // updates if the key exists and creates if it doesn't
        Setting::upsert([
            ['key' => 'file_acceptedFormats', 'value' => Json::encode($validatedData['file_acceptedFormats']) ?? ''],
            ['key' => 'file_uploadLimit', 'value' => $validatedData['file_uploadLimit'] ?? ''],
            ['key' => 'file_chosenFoldering', 'value' => Json::encode($validatedData['file_chosenFoldering']) ?? ''],
        ], ['key']);

        return response(null, 200);
    }

    private function envUpdate($key, $value){
        $path = base_path('.env');

        // if $value is a boolean we convert it to string
        if (gettype($value) == 'boolean'){
            $value = $value ? 'true' : 'false';
            if (file_exists($path)){
                $envVal = env($key) ? 'true' : 'false';
                file_put_contents(
                    $path,
                    str_replace($key . '=' . $envVal, $key . '=' . $value, file_get_contents($path))
                );
            }
        }else{
            if (file_exists($path)){
                file_put_contents(
                    $path,
                    str_replace($key . '=' . env($key), $key . '=' . $value, file_get_contents($path))
                );
            }
        }
    }

    public function menu(){
        $menus = Menu::get();
        $categories = Category::latest()->take(15)->get();
        $tags = Tag::latest()->take(15)->get();

        return Inertia::render('Admin/Settings/Menu', compact('menus', 'categories', 'tags'));
    }

}
