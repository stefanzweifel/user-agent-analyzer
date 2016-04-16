<?php

Route::get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@index',
]);

Route::post('resource', [
    'as'   => 'process.store',
    'uses' => 'ProcessController@store',
]);

Route::get('resource/{process}', [
    'as'   => 'process.show',
    'uses' => 'ProcessController@show',
]);

Route::patch('resource/{process}/', [
    'as'   => 'process.update',
    'uses' => 'ProcessController@update',
]);

Route::get('resource/{process}/downloads/xls', [
    'as'   => 'process.downloads.xls',
    'uses' => 'ProcessXlsDownloadsController@index',
]);

Route::get('resource/{process}/downloads/csv', [
    'as'   => 'process.downloads.csv',
    'uses' => 'ProcessCsvDownloadsController@index',
]);

Route::get('terms-of-service', [
    'as' => 'terms',
    'uses' => 'PagesController@terms',
]);
