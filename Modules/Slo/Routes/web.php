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

Route::get('/addNewIdRange', 'IdRangeController@create')->name('idRange.create');
Route::get('/addNewIdRange/{id}', 'IdRangeController@show')->name('idRange.show');
Route::post('/idRangeAddForm', 'IdRangeController@store');
Route::get('/idRangeList', 'IdRangeController@index')->name('idRange.list');
Route::get('/trashIdRange', 'IdRangeController@trash');
Route::get('/holdIdRange', 'IdRangeController@hold');
Route::get('/idRangeTrashList', 'IdRangeController@trashList')->name('idRange.trash');

Route::get('/addNewGroup', 'GroupesController@create')->name('groups.create');
Route::get('/select/batch2/{id}', 'StudentController@loadBatches');

Route::get('/addNewStudent', 'StudentController@create')->name('student.create');
Route::get('/select/departments/{id}', 'StudentController@loadDepartments');
Route::get('/select/courses/{id}', 'StudentController@loadCourses');
Route::get('/select/batch/{id}', 'StudentController@loadBatches');
Route::get('/getDepartmentCode', 'StudentController@getDepartmentCode');
Route::get('/getStdSeriel', 'StudentController@getStdSeriel');
Route::get('/getMiddleId', 'StudentController@getMiddleId');
Route::get('/studentsList', 'StudentController@index');
Route::get('/stdUpdate/{id}', 'StudentController@show');
Route::post('/addNewStudent', 'StudentController@addNewStudent');

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

Route::get('/courseReq', 'CoursereqController@create')->name('courseReq.create');
Route::get('/courseReq/{id}', 'CoursereqController@show')->name('courseReq.show');
Route::post('/addFieldstoC', 'CoursereqController@store');
Route::get('/delField', 'CoursereqController@destroy');
Route::get('/genaralReq', 'CoursereqController@index');

Route::get('/courseCategoryTypes', 'CoursecategorytypesController@index')->name('hospital.list');