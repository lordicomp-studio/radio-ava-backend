<?php

namespace App\Traits;

use App\Models\View;
use hexydec\agentzero\agentzero;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

trait IsViewable
{
    /**
     * Inserts a view with the current model id and type
     */
    public function setView($ip){
        $ua = request()->header('User-Agent');
        $browser = agentzero::parse($ua);

        return View::create([
            'device' => $browser->category,
            'platform' => $browser->platform,
            'browser' => $browser->browser,
            'ip' => $ip,
            'viewable_id' => $this->id,//$viewableId,
            'viewable_type'=> static::class,//$viewableType,
            'user_id' => Auth::guard('sanctum')->check() ? Auth::guard('sanctum')->id() : null,
        ]);
    }

    /**
     * checks if the model has had a view in the recent 24 hours
     */
    public function isViewed($ip):bool{
        $check = View::where([
            'ip'=> $ip,
            'viewable_id'=> $this->id,//$viewableId,
            'viewable_type'=> static::class,//$viewableType,
        ])
            ->where('created_at', '>', Carbon::today())
            ->first();

        return (bool)$check;
//        return $check ? true : false;
    }

}
