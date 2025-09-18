<?php


namespace App\Helpers;


use App\Models\Message;
use Illuminate\Support\Facades\Config;

class FileManager
{
    public static array $imageFormats = [
        'png', 'jpg', 'webp',
        'jpeg', 'tiff', 'raw',
        'eps', 'bitmap',
    ];

    public static array $videoFormats = [
        'mp4', 'mov', 'mpeg-4',
        'avi', 'mkv', 'avi',
        'flv', 'wmv', 'avchd',
        'webm',
    ];

    public static function getResults($path){
        $results = scandir($path);
        $results = array_diff($results, array('.', '..')); //remove the first '.' and '..' items.

        $resultsWithInfo = [];
        foreach ($results as $result){
            $resultsWithInfo[] = self::getPathInfo($path . "/$result");
        }

        return $resultsWithInfo;
    }

    public static function getPathInfo($path): array|null{
        $result = [];

        if (!file_exists($path)) return null;

        // add path and name.
        $result['path'] = $path;
        $pathSplit = explode('/', $path);
        $result['name'] = $pathSplit[count($pathSplit) - 1];

        // add file type
        if (is_dir($path)){
            $result['type'] = 'dir';
        }elseif (self::isImage($path)){
            $result['type'] = 'image';
        }elseif (self::isVideo($path)){
            $result['type'] = 'video';
        }else {
            $result['type'] = 'document';
        }

        // add size
        $result['size'] = $result['type'] === 'dir' ? '-' : filesize($result['path']);

        // add time
        $result['time'] = date ("F d Y H:i:s.", filemtime($result['path']));

        return $result;
    }

    public static function getMessageFileInfo(Message $message): array|string|null
    {
        if ($message->file_url){
            $ticketsPath = config('constants.ticketsPath');
            $fileInfo = self::getPathInfo($ticketsPath . $message->file_url);

            if (!$fileInfo){
                return 'missing file';
            } else{
                $fileInfo['path'] = self::shortenPath($fileInfo['path'], $ticketsPath);
                return $fileInfo;
            }
        }else{
            return null;
        }
    }

    public static function isImage($name) : bool{
        $loweredName = strtolower($name);

        foreach (self::$imageFormats as $format){
            if (str_ends_with($loweredName, $format)){
                return true;
            }
        }
        return false;
    }

    private static function isVideo($name) : bool{
        $loweredName = strtolower($name);

        foreach (self::$videoFormats as $format){
            if (str_ends_with($loweredName, $format)){
                return true;
            }
        }
        return false;
    }

    public static function sortResults($results):array{
        $sortedDirs = collect($results)->where('type', 'dir')->sortBy('name');

        $sortedFiles = collect($results)->where('type', '!=', 'dir')->sortBy('name');

        $sorted = $sortedDirs->merge($sortedFiles);

        return array_values($sorted->all());
    }

    public static function shortenResultsPaths($results, string $basePath){
        return array_map(function ($result) use ($basePath){
            $result['path'] = self::shortenPath($result['path'], $basePath);
            return $result;
        }, $results);
    }

    /**
    * shortens the result paths by the $basePath
     */
    public static function shortenPath(string $path, string $basePath): string{
        return substr($path, strlen($basePath));
    }

    // this method is used so that files don't replace the old files because of similar names.
    public static function makeNewFileName($name, $extension, $dirPath):string{
        $nameWithoutExtension = pathinfo($name, PATHINFO_FILENAME);
        $counter = 0;

        $temp = $nameWithoutExtension . '.' . $extension;
        while(file_exists($dirPath . "/$temp")){
            $counter++;
            $temp = $nameWithoutExtension . ' ' . "($counter)" . '.' . $extension;
        };
        return $temp;
    }

    // this method is used so that folders don't replace the old folders because of similar names.
    public static function makeNewDirName($name, $dirPath):string{
        $counter = 0;

        $temp = $name;
        while(is_dir($dirPath . "/$temp")){
            $counter++;
            $temp = $name . ' ' . "($counter)";
        };
        return $temp;
    }

    public static function shortenResultPath($result):string{
        $storagePathLength = strlen(Config::get('constants.basePath'));
        $pathLength = strlen($result['path']);
        return substr($result['path'], $storagePathLength, $pathLength);
    }

    public static function getFileManagerPageHeaderData(){
        return [
            'name' => env('APP_NAME'),
            'space' => [
                'total' => disk_total_space(getcwd()),
                'free' => disk_free_space(getcwd()),
            ],
        ];
    }

}
