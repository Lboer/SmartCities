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
    Route::get("/dashboard", "DashboardController@index")->name("dashboard");

    Route::get("/overview", "OverviewController@index")->name("overview");
    Route::get('/overview/add', 'OverviewController@showAddForm')->name('overview.add_form');
    Route::post('/overview/add', 'OverviewController@add')->name('overview.add');
    Route::get('/overview/{bin}/edit', 'OverviewController@showUpdateForm')->name('overview.edit_form');
    Route::get('/overview/{bin}', 'OverviewController@showBin')->name('overview.view');
    Route::patch('/overview/{bin}', 'OverviewController@update')->name('overview.edit');
    Route::delete('/overview/{bin}', 'OverviewController@delete')->name('overview.delete');

    Route::get("/map", "MapController@index")->name("map");
});
