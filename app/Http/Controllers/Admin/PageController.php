<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PageIndexResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $pages = PageIndexResource::collection(
            Page::with('photo', 'creator', 'creator.profilePhoto')
                ->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Pages/Index', [
            'data' => json_decode(json_encode($pages)),
            'baseLink' => '/admin/pages/indexDataApi',
            'type' => 'page',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $pages = PageIndexResource::collection(
            Page::with('photo', 'creator', 'creator.profilePhoto')
                ->where('name', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $pages;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Pages/CreateForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required | min:8 | max:255',
            'slug' => 'nullable | min:8 | max:255 | unique:pages',
            'photoId' => 'nullable | integer',
            'description' => 'nullable | string',
        ]);

        $newPage = [
            'name' => $fields['name'],
            'medium_id' => $fields['photoId'],
            'creator_id' => auth()->user()->id,
            'description' => $fields['description'] ?? null,
        ];

        if (isset($fields['slug'])){
            $newPage['slug'] = $fields['slug'];
        }

        Page::create($newPage);

        return response(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return string
     */
    public function show(Page $page)
    {
        return 'pages show. id: ' . $page->id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Inertia\Response
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Pages/CreateForm', [
            'isEdit' => true,
            'formerData' => $page->where('id', $page->id)->with('photo')->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $fields = $request->validate([
            'name' => 'required | min:8 | max:255',
            'slug' => ['nullable', 'min:8', 'max:255', Rule::unique('pages')->ignore($page->id)],
            'photoId' => 'nullable | integer',
            'description' => 'nullable | string',
        ]);

        $page->name = $fields['name'];
        $page->medium_id = $fields['photoId'];
//        $page->creator_id = auth()->user()->id;
        $page->description = $fields['description'] ?? null;
        if (isset($fields['slug'])){
            $page->slug = $fields['slug'];
        }
        $page->save();

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Page::destroy($request->toArray());
        return response(null, 200);
    }
}
