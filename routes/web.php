<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ActivityController;
use Intervention\Image\Facades\Image;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/music/music', function () {
    return view('/music/music');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// usage inside a laravel route
Route::get('/jpg', function()
{
    $img = Image::make('https://images.pexels.com/photos/4273439/pexels-photo-4273439.jpeg')->resize(300, 200); // 這邊可以隨便用網路上的image取代
    return $img->response('jpg');
});

Route::get('/manageMember/manageMembers', function () {
    return view('/manageMember/manageMembers');
});
Route::get('/manageMember/manageMembers', [MemberController::class, 'index']);
Route::post('/manageMember/manageMembers', [MemberController::class, 'update']);
Route::get('/manageMember/deleteMembers/{id}', [MemberController::class, 'deleteMembers']);

//manageNews
Route::get('/openModel/{id}', [NewsController::class, 'openModel']);
Route::get('/manageNews/manageNews', [NewsController::class, 'index']);
Route::get('/manageNews/manageNews/{id}', [NewsController::class, 'info']);
Route::delete('/manageNews/manageNews', [NewsController::class, 'delete']);
Route::post('/manageNews/manageNews', [NewsController::class, 'update']);
Route::post('/manageNews/manageNews/upload', [NewsController::class, 'upload'])
        ->name('ckeditor.upload');
// Route::post('/manageNews/manageNews/create', [NewsController::class, 'store']);
// Route::post('/manageNews/manageNews/upload', [NewsController::class, 'uploadimage'])
//         ->name('ckeditor.upload');

//manageActivity
Route::get('/manageActivity/manageActivity/{id}', [ActivityController::class, 'info']);
Route::post('/manageActivity/manageActivity', [ActivityController::class, 'update']);
Route::get('/manageActivity/deleteActivity/{id}', [ActivityController::class, 'deleteActivity']);
Route::delete('/manageActivity/manageActivity', [ActivityController::class, 'delete']);
Route::get('/manageActivity/manageActivity', [ActivityController::class, 'index']);
Route::post('/manageActivity/manageActivity', [ActivityController::class, 'storeMultiple']);//多筆上傳圖
Route::get('/manageActivity/activityUpload', [ActivityController::class, 'create']);
Route::post('/manageActivity/activityUpload', [ActivityController::class, 'storeMultiple']);//多筆上傳圖
Route::get('/manageActivity/deleteImg/{id}', [ActivityController::class, 'deleteImg']);