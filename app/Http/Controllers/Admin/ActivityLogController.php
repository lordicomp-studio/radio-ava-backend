<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ActivityLogIndexResource;
use App\Models\Article;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductEditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{

    public function index()
    {
        $activityLog = ActivityLogIndexResource::collection(
            Activity::with(['causer', 'causer.profilePhoto',
                    'subject' => function ($query) {
                        $query->morphWith([
                            Article::class => ['cover'],
                            User::class => ['profilePhoto'],
                            Product::class => ['cover'],
                            Page::class => ['photo'],
                            ProductEditRequest::class => ['cover'],
                        ]);
                    }
                ])
                ->latest()->paginate(15)
        )->resource;

        return Inertia::render('Admin/ActivityLog/Index', [
            'data' => json_decode(json_encode($activityLog)),
            'baseLink' => '/admin/activityLogs/indexDataApi',
            'type' => 'activityLog',
        ]);
    }

    public function indexDataApi()
    {
        $searchText = $request->filters['searchText'] ?? '';
        $activityLog = ActivityLogIndexResource::collection(
            Activity::/*where('id', 'LIKE', "%$searchText%")
                ->*/with('causer', 'causer.profilePhoto', 'subject')
                ->latest()->paginate(15)
        )->resource;

        return $activityLog;
    }

    public function show(Activity $activity)
    {
        return $activity;
    }
}
