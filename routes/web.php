<?php

use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::middleware(['auth'])->group(function(){
    Route::post('/tguser/{tguser}/update', [App\Http\Controllers\TguserController::class, 'update']);
    // Route::get('tguser',[App\Http\Controllers\TguserController::class,'index']);
    Route::resource('tguser',App\Http\Controllers\TguserController::class);
});
Route::get('/test',[BotController::class,'create']);
Route::post('/botupdate',[BotController::class,'update']);
Route::post('/sendmessage',[BotController::class,'sendmassage']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
