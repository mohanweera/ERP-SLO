<?php

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

    Route::prefix('academic')->group(function() {

        Route::get('/faculty', 'FacultyController@index')->name('faculty.list');
        Route::post('/faculty', 'FacultyController@index')->name('faculty.fetch');
        Route::get('/faculty/trash', 'FacultyController@trash')->name('faculty.list.trash');
        Route::post('/faculty/trash', 'FacultyController@trash')->name('faculty.list.trash');
        Route::get('/faculty/create', 'FacultyController@create')->name('faculty.add');
        Route::post('/faculty/create', 'FacultyController@store')->name('faculty.store');
        Route::get('/faculty/edit/{id}', 'FacultyController@edit')->name('faculty.edit');
        Route::post('/faculty/edit/{id}', 'FacultyController@update')->name('faculty.update');
        Route::post('/faculty/delete/{id}', 'FacultyController@delete')->name('faculty.delete');
        Route::post('/faculty/restore/{id}', 'FacultyController@restore')->name('faculty.restore');
        Route::post('/faculty/search_data', 'FacultyController@searchData')->name('faculty.search.data');

        Route::get('/department', 'DepartmentController@index')->name('department.list');
        Route::post('/department', 'DepartmentController@index')->name('department.fetch');
        Route::get('/department/trash', 'DepartmentController@trash')->name('department.list.trash');
        Route::post('/department/trash', 'DepartmentController@trash')->name('department.list.fetch');
        Route::get('/department/create', 'DepartmentController@create')->name('department.add');
        Route::post('/department/create', 'DepartmentController@store')->name('department.store');
        Route::get('/department/edit/{id}', 'DepartmentController@edit')->name('department.edit');
        Route::post('/department/edit/{id}', 'DepartmentController@update')->name('department.update');
        Route::post('/department/delete/{id}', 'DepartmentController@delete')->name('department.delete');
        Route::post('/department/restore/{id}', 'DepartmentController@restore')->name('department.restore');
        Route::post('/department/search_data', 'DepartmentController@searchData')->name('department.search.data');

    });
});

