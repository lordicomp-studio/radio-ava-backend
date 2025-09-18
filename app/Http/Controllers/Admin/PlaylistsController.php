<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PlaylistIndexResource;
use App\Models\Album;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Tag;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlaylistsController extends Controller
{

    public function index(){
        $playlists = PlaylistIndexResource::collection(
            Playlist::with('creator', 'creator.profilePhoto', 'photo')
                ->latest()
                ->paginate(15)
        )->resource;

        return Inertia::render('Admin/Playlists/Index', [
            'data' => json_decode(json_encode($playlists)),
            'baseLink' => '/admin/playlists/indexDataApi',
            'type' => 'playlist',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $playlists = PlaylistIndexResource::collection(
            Playlist::where('name', 'LIKE', "%$searchText%")
                ->with('creator', 'creator.profilePhoto', 'photo')
                ->latest()
                ->paginate(15)
        )->resource;

        return $playlists;
    }

    public function create()
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Playlists/CreateForm',
            compact('allCategories', 'allTags'));
    }

    private function getCategories(string $searchText, int $categoryCount){
        return Category::where('name', 'LIKE', "%$searchText%")->take($categoryCount)->latest()->get();
    }

    private function getTags(string $searchText, int $tagCount){
        return Tag::where('name', 'LIKE', "%$searchText%")->take($tagCount)->latest()->get();
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'photo_id' => 'nullable|integer|exists:media,id',
            'tracks' => 'nullable|array',
            'tracks.*' => 'nullable|integer|exists:tracks,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $fields['creator_id'] = auth()->id();
        $fields['release_date'] = isset($fields['release_date']) ? Verta::parse($fields['release_date'])->toCarbon() : null;

        DB::beginTransaction();

        try {
            $newPlaylist = Playlist::create($fields);

            $newPlaylist->categories()->sync($fields['categories']);
            $newPlaylist->tags()->sync($fields['tags']);

            $newPlaylist->tracks()->sync($fields['tracks']);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function show(Playlist $playlist)
    {
        return $playlist;
    }

    public function edit(Playlist $playlist)
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Playlists/CreateForm', [
            'isEdit' => true,
            'formerData' => $playlist->where('id', $playlist->id)->with('photo', 'tracks', 'tags', 'categories')->first(),
            'allCategories' => $allCategories,
            'allTags' => $allTags,
        ]);
    }

    public function update(Request $request, Playlist $playlist)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'slug' => ['nullable', 'string', Rule::unique('albums')->ignore($playlist->id)],
            'is_published' => 'nullable|boolean',
            'photo_id' => 'nullable|integer|exists:media,id',
            'tracks' => 'nullable|array',
            'tracks.*' => 'nullable|integer|exists:tracks,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $fields['release_date'] = isset($fields['release_date']) ? Verta::parse($fields['release_date'])->toCarbon() : null;

        DB::beginTransaction();

        try {
            $playlist->update($fields);

            $playlist->categories()->sync($fields['categories']);
            $playlist->tags()->sync($fields['tags']);

            $playlist->tracks()->sync($fields['tracks']);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Playlist::destroy($request->toArray());
        return response(null, 200);
    }

}
