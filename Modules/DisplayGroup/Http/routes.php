<?php

Route::group(['middleware' => 'web', 'prefix' => 'displaygroup', 'namespace' => 'Modules\DisplayGroup\Http\Controllers'], function () {
    Route::get('/', 'DisplayGroupController@index');
    Route::get('/data', 'DisplayGroupController@data');
});