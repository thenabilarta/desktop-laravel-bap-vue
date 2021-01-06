<?php

Route::group(['middleware' => 'web', 'prefix' => 'mediax', 'namespace' => 'Modules\MediaX\Http\Controllers'], function () {
    Route::get('/', 'MediaXController@index');
    Route::get('/data', 'MediaXController@data');
    Route::post('/', 'MediaXController@addmedia');
    Route::post('/edit', 'MediaXController@edit');
    Route::get('/delete/{id}', 'MediaXController@delete');
});
