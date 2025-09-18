<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Medium;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileManagerController extends Controller
{

    public function index(){
        $path = '';
        $results = FileManager::getResults(Config::get('constants.basePath'));
        $results = FileManager::sortResults($results);

        $settings = collect(
            Setting::whereIn('key', ['file_acceptedFormats', 'file_uploadLimit', 'file_chosenFoldering'])->get(['key', 'value'])
        );

        $projectData = FileManager::getFileManagerPageHeaderData();

        return Inertia::render('Admin/FileManager',
            compact('results', 'path', 'settings', 'projectData'));
    }

    public function makeFolder(Request $request){
        $request->validate([
            'folderName' => 'required',
            'path' => 'nullable',
        ]);

        $folderName = $request->folderName;
        $path = Config::get('constants.basePath') . $request->path;

        if (is_dir($path . "/$folderName") || file_exists($path . "/$folderName")){
            return response(['errors' => ['folderName' => ['نام مشابه وجود دارد.',],]], 500);
        }else{
            mkdir($path . "/$folderName");
        }

        return response(null, 200);
    }

    public function getPathResults(Request $request){
        $request->validate([
            'path' => 'nullable',
        ]);

        $path = Config::get('constants.basePath') . $request->path;

        if (is_dir($path)){
            $results = FileManager::getResults($path);
            $results = FileManager::sortResults($results);

            return response([
                'results' => $results,
                'path' => $request->path,
            ], 200);
        }else{
            return response('not a directory', 400);
        }
    }

    public function getFile(Request $request){
        $request->validate([
            'path' => 'required',
        ]);

        $storagePathLength = strlen(storage_path() . '/app');
        $pathLength = strlen($request->path);
        $fileShortPath = substr($request->path, $storagePathLength, $pathLength);

        return Storage::download($fileShortPath);
    }

    public function upload(Request $request){
        $request->validate([
            'path' => 'nullable',
            'file' => 'required',
        ]);

        $file = $request->file('file');

        $path = Config::get('constants.basePath') . $request->path;
        $name = FileManager::makeNewFileName($file->getClientOriginalName(), $file->getClientOriginalExtension(), $path);

        $file->move($path , $name);

        return response(null, 200);
    }

    public function deleteAll(Request $request){
        // $request is an array of results
        foreach ($request->toArray() as $result){
            $storagePathLength = strlen(storage_path() . '/app');
            $pathLength = strlen($result['path']);
            $fileShortPath = substr($result['path'], $storagePathLength, $pathLength);

            if (is_dir($result['path'])){
                Storage::deleteDirectory($fileShortPath);

                // if the folder contains gallery items then they should be deleted from database.
                $removedFirstPublicFolderFromPath = substr($fileShortPath, strlen("/public"));
                $galleryItems = Medium::where('url', 'LIKE', "$removedFirstPublicFolderFromPath%")->get(['id']);
                if ($galleryItems){
                    Medium::destroy($galleryItems);
                }
            }else{
                Storage::delete($fileShortPath);

                // if the file is a gallery item, then it is deleted from database.
                $removedFirstPublicFolderFromPath = substr($fileShortPath, strlen("/public"));
                $galleryItem = Medium::where('url', $removedFirstPublicFolderFromPath)->first();
                if ($galleryItem){
                    $galleryItem->delete();
                }
            }
        }

        return response(null, 200);
    }

    public function copyCut(Request $request){
        $request->validate([
            'type' => 'required',
            'files' => 'required',
            'destination' => 'nullable',
        ]);

        $shortDestination = '' . $request->destination;
        $fullDestination = Config::get('constants.basePath') . $request->destination;


        foreach ($request['files'] as $result){
            $fileShortPath = FileManager::shortenResultPath($result);

            $fileName = explode('/', $result['path']);
            $fileName = $fileName[count($fileName) - 1];

            if (is_dir($result['path'])){
                $fileName = FileManager::makeNewDirName($fileName, $fullDestination);
                if ($this->isDestinationSubfolder($result['path'], $fullDestination)){
                    continue;
                }

                if ($request->type === 'copy'){
                    File::copyDirectory($result['path'], $fullDestination . "/$fileName");
                }elseif($request->type === 'cut'){
                    File::moveDirectory($result['path'], $fullDestination . "/$fileName");
                }

            }
            else{
                $extension = explode('.', $fileName);
                $extension = $extension[count($extension) - 1];
                $fileName = FileManager::makeNewFileName($fileName, $extension, $fullDestination);

                if ($request->type === 'copy'){
                    Storage::copy($fileShortPath, $shortDestination . "/$fileName");
                }elseif($request->type === 'cut'){
                    Storage::move($fileShortPath, $shortDestination . "/$fileName");

                    // if a file is bering moved and it is a gallery item, them the db url is updated.
                    $removedFirstPublicFolderFromPath = substr($fileShortPath, strlen("/public"));
                    $galleryItem = Medium::where('url', $removedFirstPublicFolderFromPath)->first();
                    if ($galleryItem){
                        $galleryItem->update([
                            'url' => substr( "$shortDestination/$fileName", strlen("/public")),
                        ]);
                    }
                }
            }
        }

        return response(null, 200);
    }

    public function rename(Request $request){
        $validated = $request->validate([
            'oldName' => 'required',
            'newName' => 'required',
            'path' => 'nullable',
        ]);

        $from = $validated['path'] . '/' . $validated['oldName'];
        $to = $validated['path'] . '/' . $validated['newName'];

        Storage::move($from, $to);

        // if the file is a gallery item, then it is deleted from database.
        $removedFirstPublicFolderFromPath = substr($from, strlen("/public"));
        $galleryItem = Medium::where('url', $removedFirstPublicFolderFromPath)->first();
        if ($galleryItem){
            $galleryItem->update([
                'name' => $validated['newName'],
                'url' => substr($to, strlen("/public")),
            ]);
        }

        return response(null, 200);
    }

    // checks if we are copying a folder to a subfolder
    private function isDestinationSubfolder($folder, $destination):bool{
        return str_starts_with($destination, $folder);
    }

}
