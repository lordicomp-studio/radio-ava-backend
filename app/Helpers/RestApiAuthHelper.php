<?php

namespace App\Helpers;

use hexydec\agentzero\agentzero;
use Jenssegers\Agent\Agent;
use Laravel\Sanctum\PersonalAccessToken;

class RestApiAuthHelper
{
    public static function makeNewPersonalAccessToken($user, string $ip): string
    {
        $ua = request()->header('User-Agent');
        $browser = agentzero::parse($ua);

        $token = $user->createToken("API TOKEN");

        $tokenModel = PersonalAccessToken::where('id', $token->accessToken->id)->update([
            'ip' => $ip,
            'device' => $browser->category,
            'platform' => $browser->platform,
            'browser' => $browser->browser,
        ]);

        return $token->plainTextToken;
    }

}
