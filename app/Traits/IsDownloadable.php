<?php

namespace App\Traits;

use App\Models\DownloadRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait IsDownloadable
{
    /**
     * Inserts a download record with the current model id and type
     */
    public function createDownloadRecord(){
        return DownloadRecord::create([
            'downloader_id' => Auth::check() ? Auth::id() : null,
            'downloadable_id' => $this->id,
            'downloadable_type'=> static::class,
        ]);
    }

    /**
     * checks if the model has had a download in the recent 24 hours
     */
    public function isRecentlyDownloaded():bool{
        return DownloadRecord::where([
            'downloader_id'=> Auth::id(),
            'downloadable_id'=> $this->id,
            'downloadable_type'=> static::class,
        ])
            ->where('created_at', '>', Carbon::today())
            ->exists();
    }

}
