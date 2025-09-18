<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleStoreAndUpdateRequest;
use App\Http\Resources\Admin\ArticleIndexResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{

    public function index()
    {
        $articles = ArticleIndexResource::collection(
            Article::with('photo', 'creator', 'creator.profilePhoto')->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Articles/Index', [
            'data' => json_decode(json_encode($articles)),
            'baseLink' => '/admin/articles/indexDataApi',
            'type' => 'article',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $articles = ArticleIndexResource::collection(
            Article::with('photo', 'creator', 'creator.profilePhoto')
                ->where('title', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $articles;
    }

    public function create()
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Articles/CreateForm',
            compact('allCategories', 'allTags'));
    }

    private function getCategories(string $searchText, int $categoryCount){
        return Category::where('name', 'LIKE', "%$searchText%")->take($categoryCount)->latest()->get();
    }

    private function getTags(string $searchText, int $tagCount){
        return Tag::where('name', 'LIKE', "%$searchText%")->take($tagCount)->latest()->get();
    }

    public function store(ArticleStoreAndUpdateRequest $request)
    {
        $fields = $request->safe()->except([
            'photoId', 'status', 'useRealDate', 'published_at',
        ]);

        $fields['cover_id'] = $request->photoId;
        $fields['creator_id'] = auth()->id();
        $fields['status'] = $request->status;
        $fields['published_at'] = $request->useRealDate ? now() : $request->published_at;

        $newArticle = Article::create($fields);
        $newArticle->categories()->sync($fields['categories']);
        $newArticle->tags()->sync($fields['tags']);

        return response(null, 200);
    }


    public function show(Article $article)
    {
        return 'article show. id: ' . $article->id;
    }

    public function edit(Article $article)
    {
        $allCategories = $this->getCategories('', 15);
        $allTags = $this->getTags('', 15);

        return Inertia::render('Admin/Articles/CreateForm', [
            'isEdit' => true,
            'formerData' => $article->where('id', $article->id)->with('photo', 'categories', 'tags')->first(),
            'allCategories' => $allCategories,
            'allTags' => $allTags,
        ]);
    }

    public function update(ArticleStoreAndUpdateRequest $request, Article $article)
    {
        $fields = $request->safe()->except([
            'photoId', 'status', 'useRealDate', 'published_at',
        ]);

        $fields['cover_id'] = $request->photoId;
        $fields['status'] = $request->status;
        $fields['published_at'] = $request->useRealDate ? now() : $request->published_at;

        $article->update($fields);

        $article->categories()->sync($fields['categories']);
        $article->tags()->sync($fields['tags']);

        return response(null, 200);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Article::destroy($request->toArray());
        return response(null, 200);
    }
}
