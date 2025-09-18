<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TagIndexResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{

    public function index(){
        $tags = TagIndexResource::collection(
            Tag::with('user', 'user.profilePhoto')->latest()->paginate(15)
        )->resource;

        return Inertia::render('Admin/Tags/Index', [
            'tags' => json_decode(json_encode($tags)),
            'baseLink' => '/admin/tags/indexDataApi',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $tags = TagIndexResource::collection(
            Tag::where('name', 'LIKE', "%$searchText%")
                ->with('user', 'user.profilePhoto')->latest()->paginate(15)
        )->resource;

        return $tags;
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
            'name'=>'required | min:3 | max:255',
        ]);

        $newTag = Tag::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response($newTag, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name'=>'required | min:3 | max:255',
        ]);

        $tag->update([
            'name'=> $request->name,
            'user_id' => auth()->id(),
        ]);

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Tag::destroy($request->toArray());
        return response(null, 200);
    }


}
