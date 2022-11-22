<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Admin
Route::post('admin/register',[AdminController::class,'register']);
Route::post('admin/login',[AdminController::class,'login']);
Route::middleware(['auth:sanctum','abilities:admin'])->group(function () {
    Route::get('admin',[AdminController::class,'getAdmin']);
    Route::post('admin/logout',[AdminController::class,'logout'])->middleware(['auth:sanctum','abilities:admin']);
    Route::put('admin',[AdminController::class,'updateProfile'])->middleware(['auth:sanctum','abilities:admin']);
    Route::delete('admin',[AdminController::class,'deleteAccount'])->middleware(['auth:sanctum','abilities:admin']);
    Route::resource('kategori', KategoriController::class)->except(
        ['create','edit','index','show']
    );
    Route::resource('tiket', TiketController::class)->except(
        ['create','edit','index','show']
    );
});

//User
Route::post('user/register',[UserController::class,'register']);
Route::post('user/login',[UserController::class,'login']);
Route::middleware(['auth:sanctum','abilities:user'])->group(function () {
    Route::get('user',[AdminController::class,'getUser']);
    Route::post('user/logout',[AdminController::class,'logout'])->middleware(['auth:sanctum','abilities:user']);
    Route::put('user',[AdminController::class,'updateProfile'])->middleware(['auth:sanctum','abilities:user']);
    Route::delete('user',[AdminController::class,'deleteAccount'])->middleware(['auth:sanctum','abilities:user']);
    Route::resource('pesanan', PesananController::class)->except(
        ['create','edit']
    );
});



//Public
Route::get('kategori',[KategoriController::class,'index']);
Route::get('kategori',[KategoriController::class,'show']);
Route::get('admins',[AdminController::class,'getAllAdmin']);
Route::get('users',[UserController::class,'getAllUser']);
Route::get('tiket',[TiketController::class,'index']);
Route::get('tiket/{id}',[TiketController::class,'show']);




