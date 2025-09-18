<?php

namespace App\Providers;

use App\Listeners\TestListener;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Reverb\Events\MessageReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share('flash', function () {
            return [
                'success' => Session::get('success'),
                'error' => Session::get('error'),
            ];
        });
        Inertia::share([
            'locale' => function () {
                return config('app.locale');
            },
            'resume' => function () {
                return __('resume');
            },
            'workSamples' => function () {
                return __('WorkSamples');
            },
            'navs' => function () {
                return __('navBar');
            },
            'userData' => function () {
                return __('user');
            }

        ]);

        // for listening to reverb on the server
//        Event::listen(
//            MessageReceived::class,
//            TestListener::class
//        );
    }
}
