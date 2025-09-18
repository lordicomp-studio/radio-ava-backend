<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TrackStoreAndUpdateRequest;
use App\Http\Resources\Admin\TrackIndexResource;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Track;
use App\Models\TrackFile;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TracksController extends Controller
{
    public function index()
    {
        $tracks = TrackIndexResource::collection(
            Track::with('uploader', 'artist', 'artist.photo', 'cover', 'album')
                ->latest()
                ->paginate(10)
        )->resource;

        return Inertia::render('Admin/Tracks/Index', [
            'data' => json_decode(json_encode($tracks)),
            'baseLink' => '/admin/tracks/indexDataApi',
            'type' => 'track',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $tracks = TrackIndexResource::collection(
            Track::with('uploader', 'artist', 'artist.photo', 'cover', 'album')
                ->where('name', 'LIKE', "%$searchText%")
                ->latest()
                ->paginate(10)
        )->resource;

        return $tracks;
    }

    public function create()
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Tracks/CreateForm',
            compact('allCategories', 'allTags'));
    }

    /**
     * @throws \Exception
     */
    public function store(TrackStoreAndUpdateRequest $request)
    {
        $fields = $request->safe()->except([
            'tracks', 'categories', 'tags',
        ]);

        $fields['uploader_id'] = auth()->id();

        DB::beginTransaction();

        try {
            $fields['release_date'] = isset($fields['release_date']) ? Verta::parse($fields['release_date'])->toCarbon() : null;

            $newTrack = Track::create($fields);

            $relations = $request->safe()->only(['track_files', 'categories', 'tags']);

            $newTrack->categories()->sync($relations['categories']);
            $newTrack->tags()->sync($relations['tags']);

            $trackFiles = array_map(function ($track) use ($newTrack) {
                return [
                    'track_id' => $newTrack->id,
                    'quality' => $track['quality'],
                    'file_url' => $track['file_url'],
                ];
            }, $relations['track_files']);

            TrackFile::insert($trackFiles);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function show(Track $track)
    {
        return $track;
    }

    public function edit(Track $track)
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Tracks/CreateForm', [
            'isEdit' => true,
            'formerData' => $track->where('id', $track->id)->with('artist', 'cover', 'categories', 'tags', 'trackFiles')->first(),
            'allCategories' => $allCategories,
            'allTags' => $allTags,
        ]);
    }

    private function getCategories(string $searchText, int $categoryCount){
        return Category::where('name', 'LIKE', "%$searchText%")->take($categoryCount)->latest()->get();
    }

    private function getTags(string $searchText, int $tagCount){
        return Tag::where('name', 'LIKE', "%$searchText%")->take($tagCount)->latest()->get();
    }

    public function update(TrackStoreAndUpdateRequest $request, Track $track)
    {
        $fields = $request->safe()->except([
            'tracks', 'categories', 'tags',
        ]);

        DB::beginTransaction();

        try {
            $track->update($fields);

            $relations = $request->safe()->only(['track_files', 'categories', 'tags']);

            $track->categories()->sync($relations['categories']);
            $track->tags()->sync($relations['tags']);

            $trackFiles = array_map(function ($trackItem) use ($track) {
                return [
                    'track_id' => $track->id,
                    'quality' => $trackItem['quality'],
                    'file_url' => $trackItem['file_url'],
                ];
            }, $relations['track_files']);

            TrackFile::where('track_id', $track->id)->delete();
            TrackFile::insert($trackFiles);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return response(null, 200);
    }

    public function destroy(Track $track)
    {
        $track->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Track::destroy($request->toArray());
        return response(null, 200);
    }
}
