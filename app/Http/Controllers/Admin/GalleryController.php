<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileManager;
use App\Helpers\SettingsHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\GalleryIndexResource;
use App\Models\Medium;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $media = GalleryIndexResource::collection(
            Medium::with('uploader')->latest()->paginate(21)
        )->resource;

        $baseLink = '/admin/gallery/indexDataApi';
        // basePath is used when we are reusing fileManagerController back-end through gallery actions panel(copyCut, delete, etc).
        $basePath = config('constants.publicPath');

        return Inertia::render('Admin/Gallery/GalleryIndex', [
            'media' => json_decode(json_encode($media)),
            'baseLink' => $baseLink,
            'basePath' => $basePath,
        ]);
    }

    public function indexDataApi(Request $request){
        $filters = $request->filters;
        $media = $this->checkFilters($filters);

        return GalleryIndexResource::collection(
            $media->latest()->paginate(21)
        )->resource;
    }

    private function checkFilters($filters): ?Builder
    {
        // file name filter
        $fileName = $filters['fileName'];
        $media = Medium::with('uploader')->where('name', 'LIKE', "%$fileName%");

        // uploader name filter
        if (isset($filters['user']) && $filters['user'] != null){
            $media = $media->whereRelation('uploader', 'id', $filters['user']['id']);
        }

        // file type filter
        if ($filters['fileType'] != 'all') {
            $fileType = $filters['fileType'];
            $media = $this->filterFileType($media, $fileType);
        }

        // upload date filter
        if ($filters['uploadDate'] != 'all') {
            $uploadDate = $filters['uploadDate'];
            $media = $this->filterUploadDate($media, $uploadDate);
        }

        return $media;
    }

    private function filterFileType($query, $fileType){
        if ($fileType === 'photo'){
            return $query->whereIn('format', FileManager::$imageFormats);
        }elseif ($fileType === 'video'){
            return $query->whereIn('format', FileManager::$videoFormats);
        }
        return null;
    }

    private function filterUploadDate($query, $uploadDate){
        $timeLimit = null;
        if ($uploadDate === 'last year'){
            $timeLimit = Carbon::now()->subYear();
        }elseif ($uploadDate === 'last month'){
            $timeLimit = Carbon::now()->subMonth();
        }elseif ($uploadDate === 'last day'){
            $timeLimit = Carbon::now()->subDay();
        }
        return $query->where('created_at', '>', $timeLimit);
    }

    public function upload(Request $request){
        $request->validate([
//            'path' => 'nullable',
            'medium' => 'required',
        ]);

        $file = $request->file('medium');

        $path = Config::get('constants.publicPath');
        $settingsFoldersPath = SettingsHelper::makePathAccordingToSettings($file);

        $name = FileManager::makeNewFileName(
            $file->getClientOriginalName(), $file->getClientOriginalExtension(), $path . $settingsFoldersPath
        );

        auth()->user()->uploadedMedia()->create([
            'name' => $file->getClientOriginalName(),
            'url' => $settingsFoldersPath . $name,
            'size' => $file->getSize(),
            'format' => $file->getClientOriginalExtension(),
        ]);

        $file->move($path . $settingsFoldersPath, $name);

        return response(null, 200);
    }

    public function fileManagerRedirect(Request $request){
        $path = substr($request->path, strlen(config('constants.basePath')));
        $results = FileManager::getResults($request->path);
        $results = FileManager::sortResults($results);

        $projectData = FileManager::getFileManagerPageHeaderData();

        return Inertia::render('Admin/FileManager',
            compact('results', 'path', 'projectData'));
    }
}
