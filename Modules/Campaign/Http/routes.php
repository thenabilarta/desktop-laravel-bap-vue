<?php

Route::group(['middleware' => 'web', 'prefix' => 'campaign', 'namespace' => 'Modules\Campaign\Http\Controllers'], function () {
    Route::get('/', 'CampaignController@index');
    Route::get('/data', 'CampaignController@data');
});