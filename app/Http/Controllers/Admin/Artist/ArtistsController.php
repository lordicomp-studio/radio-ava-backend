<?php

namespace App\Http\Controllers\Admin\Artist;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ArtistIndexResource;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Tag;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArtistsController extends Controller
{

    public function index()
    {
        $artists = ArtistIndexResource::collection(
            Artist::with('photo')->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Artists/Index', [
            'data' => json_decode(json_encode($artists)),
            'baseLink' => '/admin/artists/indexDataApi',
            'type' => 'artist',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $artists = ArtistIndexResource::collection(
            Artist::with('photo')
                ->where('name', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $artists;
    }

    public function create()
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Artists/CreateForm',
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
            'name' => 'required',
            'slug' => 'nullable',
            'photo_id' => 'nullable|exists:media,id',
            'description' => 'nullable',
            'birth_date' => 'nullable',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        $fields['birth_date'] = isset($fields['birth_date']) ? Verta::parse($fields['birth_date'])->toCarbon() : null;

        $newArtist = Artist::create($fields);

        $newArtist->categories()->sync($fields['categories']);
        $newArtist->tags()->sync($fields['tags']);

        return response(null, 200);
    }


    public function show(Artist $artist)
    {
        return $artist;
    }

    public function edit(Artist $artist)
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        $artist = $artist
            ->where('id', $artist->id)
            ->with('photo', 'categories', 'tags')
            ->first();

        $artist->birth_date = verta($artist->birth_date);

        return Inertia::render('Admin/Artists/CreateForm', [
            'isEdit' => true,
            'formerData' => $artist,
            'allCategories' => $allCategories,
            'allTags' => $allTags,
        ]);
    }

    public function update(Request $request, Artist $artist)
    {
        $fields = $request->validate([
            'name' => 'required',
            'slug' => 'nullable',
            'photo_id' => 'nullable|exists:media,id',
            'description' => 'nullable',
            'birth_date' => 'nullable',
            'tags' => 'nullable|array',
            'categories' => 'nullable|array',
        ]);

        $fields['birth_date'] = isset($fields['birth_date']) ? Verta::parse($fields['birth_date'])->toCarbon() : null;

        $artist->update($fields);

        $artist->categories()->sync($fields['categories']);
        $artist->tags()->sync($fields['tags']);

        return response(null, 200);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Artist::destroy($request->toArray());
        return response(null, 200);
    }
}
