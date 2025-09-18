<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;

class SettingsHelper
{

    public static function makePathAccordingToSettings($file):string {
        $folderingSettings = json_decode(Setting::where('key', 'file_chosenFoldering')->first()?->value);
        $path = '';

        if (!$folderingSettings){
            // if file_chosenFoldering row was undefined.
            return "/";
        }

        if (in_array('آیدی آپلود کننده', $folderingSettings)){
            $path .= "/user_id-" . auth()->user()->id;
        }
        if (in_array('سال', $folderingSettings)){
            $path .= "/year-" . Carbon::now()->year;
        }
        if (in_array('ماه', $folderingSettings)){
            $path .= "/month-" . Carbon::now()->month;
        }
        if (in_array('روز', $folderingSettings)){
            $path .= "/day-" . Carbon::now()->day;
        }
        if (in_array('فرمت فایل', $folderingSettings)){
            $path .= "/format-" . $file->getClientOriginalExtension();
        }

        return $path . "/";
    }

}
