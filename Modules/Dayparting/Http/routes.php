<?php

Route::group(['middleware' => 'web', 'prefix' => 'dayparting', 'namespace' => 'Modules\Dayparting\Http\Controllers'], function () {
    Route::get('/', 'DaypartingController@index');
    Route::get('/data', 'DaypartingController@data');
});