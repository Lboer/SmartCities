<?php

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

Route::group(['middleware' => ['auth:sanctum', 'verified']], function(){
    Route::get("/dashboard", "App\Http\Controllers\DashboardController@index")->name("dashboard");

    Route::get("/overview", "App\Http\Controllers\OverviewController@index")->name("overview");
    Route::get('/overview/{garbageBin}/edit', 'App\Http\Controllers\OverviewController@showUpdateForm')->name('overview.edit_form');
    Route::patch('/overview/{garbageBin}', 'App\Http\Controllers\OverviewController@update')->name('overview.edit');
    Route::delete('/overview/{garbageBin}', 'App\Http\Controllers\OverviewController@delete')->name('overview.delete');

    Route::get("/map", "App\Http\Controllers\MapController@index")->name("map");
});
