<?php

use App\Http\Controllers\Api\ArchiveController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/get-widgets-data' , [IndexController::class , 'indexPage'] )->name('api.index');

// Auth (without tdd)
Route::group(['prefix' => 'auth'] , function (){
   Route::post('/login', [AuthController::class ,'login'])->name('api.auth.login');
   Route::post('/register', [AuthController::class ,'register'])->name('api.auth.register');
   Route::post('/me', [AuthController::class ,'me'])->name('api.auth.me');
   Route::post('/logout', [AuthController::class ,'logout'])->name('api.auth.logout');
   Route::post('/refresh', [AuthController::class ,'refresh'])->name('api.auth.refresh');
   Route::post('/forget-password', [AuthController::class ,'forgetPassword'])->name('api.auth.forget');
   Route::post('/reset-password', [AuthController::class ,'resetPassword'])->name('api.auth.reset');
   Route::post('/verify-token', [AuthController::class, 'verifyToken'])->name('api.auth.verifyToken');
});

// Auth (without tdd)
//Route::prefix('auth/social')->group(function (){
//    Route::get('/google/redirect', [SocialAuthController::class, 'googleRedirect'])->name('auth.social.google.redirect');
//    Route::get('/google/callback', [SocialAuthController::class, 'googleCallback'])->name('auth.social.google.callback');
//});

// Verification (without tdd)
Route::prefix('verify')->group(function (){
    // email verification
    Route::post('/email/sendVerification', [VerificationController::class, 'sendEmailVerification'])
        ->name('api.verify.sendEmailVerification');
    Route::get('/email/{user}', [VerificationController::class, 'verifyEmail'])
        ->middleware('signed')->name('api.verify.verifyEmail');
});


// Archive
Route::prefix('archive')->group(function (){
    Route::get('/search', [ArchiveController::class, 'search'])->name('api.archive.search');
    Route::get('/cat/{category:slug}', [ArchiveController::class, 'category'])->name('api.archive.category');
    Route::get('/tag/{tag:slug}', [ArchiveController::class, 'tag'])->name('api.archive.tag');
    Route::get('/quickSearch', [ArchiveController::class, 'quickSearch'])->name('api.archive.quickSearch');
});
