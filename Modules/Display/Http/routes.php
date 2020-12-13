<?php

Route::group(['middleware' => 'web', 'prefix' => 'display', 'namespace' => 'Modules\Display\Http\Controllers'], function () {
    Route::get('/', 'DisplayController@index');
    Route::get('/data', 'DisplayController@data');
    Route::get('/layout', 'DisplayController@layout');
    Route::post('/setlayout', 'DisplayController@setlayout');
    Route::post('/previewProfileData', 'DisplayController@previewProfileData');
    Route::post('/edit', 'DisplayController@edit');
});