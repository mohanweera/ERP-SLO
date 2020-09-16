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

Route::middleware(["auth.admin:admin", "admin.permissions:admin"])->group(function() {

    Route::get('/', 'DashboardController@index')->name('dashboard.home');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.home');

    Route::post('/country/search_data', 'CountryController@searchData')->name('country.search.data');
});

