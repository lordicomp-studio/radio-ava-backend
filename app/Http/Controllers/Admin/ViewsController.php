<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ViewIndexResource;
use App\Models\View;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewsController extends Controller
{
    public function index(){
        $views = ViewIndexResource::collection(
            View::with('user', 'user.profilePhoto', 'viewable', 'viewable.cover')
                ->latest()->paginate(15)
        )->resource;

        return Inertia::render('Admin/Views/Index', [
            'data' => json_decode(json_encode($views)),
            'baseLink' => '/admin/views/indexDataApi',
            'type' => 'view',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $views = ViewIndexResource::collection(
            View::where('ip', 'LIKE', "%$searchText%")
                ->with('user', 'user.profilePhoto', 'viewable', 'viewable.cover')
                ->latest()->paginate(15)
        )->resource;

        return $views;
    }

    /**
     * Display the specified resource.
     */
    public function show(View $view)
    {
        return $view;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(View $view)
    {
        $view->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        View::destroy($request->toArray());
        return response(null, 200);
    }

}
