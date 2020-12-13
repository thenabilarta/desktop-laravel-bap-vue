<?php

Route::group(['middleware' => 'web', 'prefix' => 'displayprofile', 'namespace' => 'Modules\DisplayProfile\Http\Controllers'], function () {

    Route::get('/', 'DisplayProfileController@index');
    Route::get('/data', 'DisplayProfileController@data');
});