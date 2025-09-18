<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryIndexResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{

    public function index(){
        $categories = CategoryIndexResource::collection(
            Category::with('user', 'user.profilePhoto', 'parent', 'photo')->latest()->paginate(15)
        )->resource;

        return Inertia::render('Admin/Categories/Index', [
            'categories' => json_decode(json_encode($categories)),
            'baseLink' => '/admin/categories/indexDataApi',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $categories = CategoryIndexResource::collection(
            Category::where('name', 'LIKE', "%$searchText%")
                ->with('user', 'user.profilePhoto', 'parent', 'photo')->latest()->paginate(15)
        )->resource;


        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | min:3 | max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'photo_id' => 'nullable|integer|exists:media,id',
        ]);

        $newCategory = Category::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'photo_id' => $request->photo_id,
        ]);

        return response($newCategory, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=>'required | min:3 | max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'photo_id' => 'nullable|integer|exists:media,id',
        ]);

        $category->update([
            'name'=> $request->name,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'photo_id' => $request->photo_id,
        ]);

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Category::destroy($request->toArray());
        return response(null, 200);
    }

}
