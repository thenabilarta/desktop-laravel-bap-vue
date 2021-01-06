<?php

Route::group(['middleware' => 'web', 'prefix' => 'mediax', 'namespace' => 'Modules\MediaX\Http\Controllers'], function () {
    Route::get('/', 'MediaXController@index');
    Route::get('/data', 'MediaXController@data');
});
