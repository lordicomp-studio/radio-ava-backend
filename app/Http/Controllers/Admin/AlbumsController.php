<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AlbumIndexResource;
use App\Models\Album;
use App\Models\Artist;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AlbumsController extends Controller
{

    public function index(){
        $albums = AlbumIndexResource::collection(
            Album::with('creator', 'creator.profilePhoto', 'artist', 'artist.photo', 'photo')
                ->latest()
                ->paginate(15)
        )->resource;

        return Inertia::render('Admin/Albums/Index', [
            'data' => json_decode(json_encode($albums)),
            'baseLink' => '/admin/albums/indexDataApi',
            'type' => 'album',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $albums = AlbumIndexResource::collection(
            Album::where('name', 'LIKE', "%$searchText%")
                ->with('creator', 'creator.profilePhoto', 'artist', 'artist.photo', 'photo')
                ->latest()
                ->paginate(15)
        )->resource;

        return $albums;
    }

    public function create()
    {
        return Inertia::render('Admin/Albums/CreateForm');
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'release_date' => 'nullable',
            'artist_id' => 'required|integer|exists:artists,id',
            'photo_id' => 'nullable|integer|exists:media,id',
            'tracks' => 'nullable|array',
            'tracks.*' => 'nullable|integer|exists:tracks,id',
        ]);

        $fields['creator_id'] = auth()->id();
        $fields['release_date'] = isset($fields['release_date']) ? Verta::parse($fields['release_date'])->toCarbon() : null;

        DB::beginTransaction();

        try {
            $newAlbum = Album::create($fields);

            $newAlbum->tracks()->sync($fields['tracks']);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function show(Album $album)
    {
        return $album;
    }

    public function edit(Album $album)
    {
        $album = $album
            ->where('id', $album->id)
            ->with('artist', 'photo', 'tracks')
            ->first();

        $album->release_date = verta($album->release_date);

        return Inertia::render('Admin/Albums/CreateForm', [
            'isEdit' => true,
            'formerData' => $album,
        ]);
    }

    public function update(Request $request, Album $album)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'slug' => ['nullable', 'string', Rule::unique('albums')->ignore($album->id)],
            'is_published' => 'nullable|boolean',
            'release_date' => 'nullable',
            'artist_id' => 'required|integer|exists:artists,id',
            'photo_id' => 'nullable|integer|exists:media,id',
            'tracks' => 'nullable|array',
            'tracks.*' => 'nullable|integer|exists:tracks,id',
        ]);

        $fields['release_date'] = isset($fields['release_date']) ? Verta::parse($fields['release_date'])->toCarbon() : null;

        DB::beginTransaction();

        try {
            $album->update($fields);

            $album->tracks()->sync($fields['tracks']);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Album::destroy($request->toArray());
        return response(null, 200);
    }

}
