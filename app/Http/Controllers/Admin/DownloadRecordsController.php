<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DownloadRecordIndexResource;
use App\Http\Resources\Admin\ViewIndexResource;
use App\Models\DownloadRecord;
use App\Models\View;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DownloadRecordsController extends Controller
{
    public function index(){
        $downloadRecords = DownloadRecordIndexResource::collection(
            DownloadRecord::with('user', 'user.profilePhoto', 'downloadable', 'downloadable.cover')
                ->latest()->paginate(15)
        )->resource;

        return Inertia::render('Admin/DownloadRecords/Index', [
            'data' => json_decode(json_encode($downloadRecords)),
            'baseLink' => '/admin/downloadRecords/indexDataApi',
            'type' => 'downloadRecord',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $downloadRecords = DownloadRecordIndexResource::collection(
            DownloadRecord::where('id', 'LIKE', "%$searchText%")
                ->with('user', 'user.profilePhoto', 'downloadable', 'downloadable.cover')
                ->latest()->paginate(15)
        )->resource;

        return $downloadRecords;
    }

    /**
     * Display the specified resource.
     */
    public function show(DownloadRecord $downloadRecord)
    {
        return $downloadRecord;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DownloadRecord $downloadRecord)
    {
        $downloadRecord->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        DownloadRecord::destroy($request->toArray());
        return response(null, 200);
    }

}
