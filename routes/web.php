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

Route::get('/', "App\Http\Controllers\DashboardController@show")->name('dashboard');
Route::get('/invest/{id}', "App\Http\Controllers\InvestController@show")->name('invest.show');
Route::get('/invest/history/{id}/{id_invest}', "App\Http\Controllers\InvestController@historyMyFx")->name('invest.history');
Route::get('/invest/daily/{id}/{id_invest}', "App\Http\Controllers\InvestController@dailyMyFx")->name('invest.daily');
Route::get("/invest/history/export/{id}/{id_invest}", "App\Http\Controllers\InvestController@export")->name('invest.export');
Route::get("/invest/daily/exportDaily/{id}/{id_invest}", "App\Http\Controllers\InvestController@exportDaily")->name('invest.exportDaily');

Route::get('/comptes', "App\Http\Controllers\ComptesController@show")->name('comptes');
Route::get('/comptes/add', "App\Http\Controllers\ComptesController@add")->name('comptes.add');
Route::post('/comptes/store', "App\Http\Controllers\ComptesController@store")->name('comptes.store');
Route::get('/comptes/edit/{id}', "App\Http\Controllers\ComptesController@edit")->name('comptes.edit');
Route::post('/comptes/update', "App\Http\Controllers\ComptesController@update")->name('comptes.update');
Route::get('/comptes/delete/{id}', "App\Http\Controllers\ComptesController@delete")->name('comptes.delete');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});