<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AlbumsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\Artist\ArtistsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadRecordsController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\PlaylistsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TracksController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewsController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::withoutMiddleware(IsAdmin::class)
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
        Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

Route::prefix('/dashboard')->group(function (){
    Route::get('/panel', [DashboardController::class, 'panel']);
});

Route::get('/documents', function (){
    return Inertia::render('Admin/Documents');
});


// Users
Route::resource('users', UserController::class);
Route::prefix('users')->group(function (){
    Route::post('/indexDataApi', [UserController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [UserController::class, 'deleteMultiple']);
});

// Roles
Route::resource('roles', RoleController::class)->except('create', 'edit');
Route::get('/roles/api/getTableData', [RoleController::class, 'getTableData']); // NOT SURE: /api is added to link because the plugin is using the simple link.

// Permissions
Route::resource('permissions', PermissionsController::class)->except('create', 'edit', 'show');
Route::prefix('permissions')->group(function (){
    Route::post('/indexDataApi', [PermissionsController::class, 'indexDataApi']);
    //Route::get('/getTableData', [PermissionsController::class, 'getTableData']);
    Route::post('/getParents', [PermissionsController::class, 'getParents']);
    Route::post('/getAllParentChildren', [PermissionsController::class, 'getAllParentChildren']);
    Route::delete('/api/deleteMultiple', [PermissionsController::class, 'deleteMultiple']);
});

// File Manager
Route::prefix('fileManager')->group(function (){
    Route::get('/', [FileManagerController::class, 'index']);
    Route::post('/makeFolder', [FileManagerController::class, 'makeFolder']);
    Route::post('/getPathResults', [FileManagerController::class, 'getPathResults']);
    Route::get('/getFile', [FileManagerController::class, 'getFile']);
    Route::post('/upload', [FileManagerController::class, 'upload']);
    Route::delete('/deleteAll', [FileManagerController::class, 'deleteAll']);
    Route::post('/copyCut', [FileManagerController::class, 'copyCut']);
    Route::post('/rename', [FileManagerController::class, 'rename']);
});

// Gallery
Route::prefix('gallery')->group(function (){
    Route::resource('/', GalleryController::class);
    Route::post('/indexDataApi', [GalleryController::class, 'indexDataApi']);
    Route::post('/upload', [GalleryController::class, 'upload']);
    Route::get('/fileManager', [GalleryController::class, 'fileManagerRedirect']); // used for redirecting to fileManager for copyCut.
});

// Categories
Route::resource('categories', CategoryController::class)->except('create', 'edit', 'show');
Route::prefix('categories')->group(function (){
    Route::post('/indexDataApi', [CategoryController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [CategoryController::class, 'deleteMultiple']);
});

// Tags
Route::resource('tags', TagController::class)->except('create', 'edit', 'show');
Route::prefix('tags')->group(function (){
    Route::post('/indexDataApi', [TagController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [TagController::class, 'deleteMultiple']);
});

// Settings
Route::prefix('settings')->group(function (){
    Route::get('/general', [SettingsController::class, 'general']);
    Route::post('/general', [SettingsController::class, 'setGeneralSettings']);
    Route::get('/seo', [SettingsController::class, 'seo']);
    Route::post('/seo', [SettingsController::class, 'setSeoSettings']);
    Route::get('/menu', [SettingsController::class, 'menu']);
    Route::get('/theme', [SettingsController::class, 'theme']);
    Route::post('/theme', [SettingsController::class, 'setThemeSettings']);
    Route::post('/file', [SettingsController::class, 'setFileSettings']);
});

// Menus
Route::resource('menus', MenusController::class)->only('store', 'update', 'destroy');
Route::prefix('menus')->group(function (){
    Route::post('/indexDataApi', [MenusController::class, 'indexDataApi']);
    Route::post('/updateMenuSubmenu', [MenusController::class, 'updateMenuSubmenu']);
});

// Pages
Route::resource('pages', PageController::class);
Route::prefix('pages')->group(function (){
    Route::post('/indexDataApi', [PageController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [PageController::class, 'deleteMultiple']);
});

// Articles
Route::resource('articles', ArticleController::class);
Route::prefix('articles')->group(function (){
    Route::post('/indexDataApi', [ArticleController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [ArticleController::class, 'deleteMultiple']);
});

// Tickets
Route::resource('tickets', TicketController::class)->except('create', 'edit');
Route::prefix('tickets')->group(function (){
    Route::post('/indexDataApi', [TicketController::class, 'indexDataApi']);
    Route::post('/{chatId}/showDataApi', [TicketController::class, 'showDataApi']);
    Route::delete('/api/deleteMultiple', [TicketController::class, 'deleteMultiple']);
});

// Messages
Route::resource('messages', MessageController::class)->only('store', 'update', 'destroy');

// Payments
Route::resource('payments', PaymentsController::class)->only('index', 'show', 'destroy');
Route::prefix('payments')->group(function (){
    Route::post('/indexDataApi', [PaymentsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [PaymentsController::class, 'deleteMultiple']);
});

// Views
Route::resource('views', ViewsController::class)->only('index', 'show', 'destroy');
Route::prefix('views')->group(function (){
    Route::post('/indexDataApi', [ViewsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [ViewsController::class, 'deleteMultiple']);
});

// Download Records
Route::resource('downloadRecords', DownloadRecordsController::class)->only('index', 'show', 'destroy');
Route::prefix('downloadRecords')->group(function (){
    Route::post('/indexDataApi', [DownloadRecordsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [DownloadRecordsController::class, 'deleteMultiple']);
});

// Activity Log
Route::model('activityLog', Activity::class); // binds the activityLog route parameter to the Activity model
Route::resource('activityLogs', ActivityLogController::class)->only('index', 'show');
Route::prefix('activityLogs')->group(function (){
    Route::post('/indexDataApi', [ActivityLogController::class, 'indexDataApi']);
});

// Artist
Route::resource('artists', ArtistsController::class);
Route::prefix('artists')->group(function (){
    Route::post('/indexDataApi', [ArtistsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [ArtistsController::class, 'deleteMultiple']);
});

// Tracks
Route::resource('tracks', TracksController::class);
Route::prefix('tracks')->group(function (){
    Route::post('/indexDataApi', [TracksController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [TracksController::class, 'deleteMultiple']);
});

// albums
Route::resource('albums', AlbumsController::class);
Route::prefix('albums')->group(function (){
    Route::post('/indexDataApi', [AlbumsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [AlbumsController::class, 'deleteMultiple']);
});

// playlists
Route::resource('playlists', PlaylistsController::class);
Route::prefix('playlists')->group(function (){
    Route::post('/indexDataApi', [PlaylistsController::class, 'indexDataApi']);
    Route::delete('/api/deleteMultiple', [PlaylistsController::class, 'deleteMultiple']);
});
