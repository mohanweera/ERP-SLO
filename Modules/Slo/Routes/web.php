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

Route::prefix('slo')->group(function() {
    Route::get('/', 'SloController@index');
});
Route::get('/addNewBatchType', 'BatchTypesController@create')->name('batchType.create');
Route::get('/addNewBatchType/{id}', 'BatchTypesController@show')->name('batchType.show');
Route::post('/addNewBatchTypes', 'BatchTypesController@store');
Route::get('/batchTypeList', 'BatchTypesController@index')->name('batchType.list');
Route::get('/trashBatchType', 'BatchTypesController@trash');
Route::get('/batchTypeTrashList', 'BatchTypesController@trashList')->name('batchType.trash');

Route::get('/addNewBatch', 'BatchesController@create')->name('batches.create');
Route::get('/addNewBatch/{id}', 'BatchesController@show')->name('batches.show');
Route::post('/batchAddForm', 'BatchesController@store');
Route::get('/loadBatchCode', 'BatchesController@loadBatchCode');
Route::get('/batchList', 'BatchesController@index')->name('batches.list');
Route::get('/trashBatch', 'BatchesController@trash');
Route::get('/batchTrashList', 'BatchesController@trashList')->name('batches.trash');

Route::get('/addNewIdRange', 'idRangeController@create')->name('idRange.create');
Route::get('/addNewIdRange/{id}', 'idRangeController@show')->name('idRange.show');
Route::post('/idRangeAddForm', 'idRangeController@store');
Route::get('/idRangeList', 'idRangeController@index')->name('idRange.list');
Route::get('/trashIdRange', 'idRangeController@trash');
Route::get('/holdIdRange', 'idRangeController@hold');
Route::get('/idRangeTrashList', 'idRangeController@trashList')->name('idRange.trash');

Route::get('/addNewGroup', 'GroupesController@create')->name('groups.create');
Route::get('/select/batch2/{id}', 'studentController@loadBatches');

Route::get('/addNewStudent', 'studentController@create')->name('student.create');
Route::get('/select/departments/{id}', 'studentController@loadDepartments');
Route::get('/select/courses/{id}', 'studentController@loadCourses');
Route::get('/select/batch/{id}', 'studentController@loadBatches');
Route::get('/getDepartmentCode', 'studentController@getDepartmentCode');
Route::get('/getStdSeriel', 'studentController@getStdSeriel');
Route::get('/getMiddleId', 'studentController@getMiddleId');
Route::post('/addNewStudent', 'studentController@addNewStudent');

Route::get('/addNewGroup', 'GroupesController@create')->name('groups.create');
Route::get('/select/batch2/{id}', 'GroupesController@loadBatches');
Route::post('/addNewGroup', 'GroupesController@store');
Route::get('/groupList', 'GroupesController@index')->name('groups.list');
Route::get('/groupsTrashList', 'GroupesController@trashList')->name('groups.trash');
Route::get('/addGroupTrash', 'GroupesController@trash');
Route::get('/addNewGroup/{id}', 'GroupesController@show')->name('groups.show');

Route::get('/addNewUpCat', 'UploadCController@create')->name('ct.create');
Route::get('/uploadCtList', 'UploadCController@index')->name('ct.list');
Route::post('/addNewUC', 'UploadCController@store');
Route::get('/addNewUpCat/{id}', 'UploadCController@show')->name('ct.show');
Route::get('/trashUpCt', 'UploadCController@trash');
Route::get('/upCatTrashList', 'UploadCController@trashList')->name('ct.trash');

Route::get('/stdUpHome', 'StdupController@index')->name('stdup.index');
Route::get('/searchUploads', 'StdupController@create')->name('stdup.searchup');
Route::post('/uploadStd', 'StdupController@store');

Route::get('/hospitalsList', 'HospitalsController@index')->name('hospital.list');
Route::get('/addNewHospital', 'HospitalsController@create')->name('hospital.create');
Route::post('/addNewHospital', 'HospitalsController@store');
Route::get('/addNewHospital/{id}', 'HospitalsController@show')->name('hospital.show');
Route::get('/hospitalsTrashList', 'HospitalsController@trashList')->name('hospital.trash');
Route::get('/trashHospital', 'HospitalsController@trash');