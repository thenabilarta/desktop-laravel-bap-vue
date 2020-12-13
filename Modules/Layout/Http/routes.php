<?php

Route::group(['middleware' => 'web', 'prefix' => 'layout', 'namespace' => 'Modules\Layout\Http\Controllers'], function () {
    Route::get('/', 'LayoutController@index');
    Route::get('/data', 'LayoutController@data');
    Route::post('/delete', 'LayoutController@delete');
});