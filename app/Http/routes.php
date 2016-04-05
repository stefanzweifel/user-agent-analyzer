<?php

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::post('resource', [
    'as' => 'process.store',
    'uses' => 'ProcessController@store'
]);

Route::get('resource/{process}/upload', [
    'as' => 'process.upload',
    'uses' => 'ProcessController@upload'
]);

Route::patch('resource/{process}/', [
    'as' => 'process.update',
    'uses' => 'ProcessController@update'
]);

Route::get('resource/{process}/', [
    'as' => 'process.show',
    'uses' => 'ProcessController@show'
]);

Route::get('resource/{process}/upload-success', [
    'as' => 'route.messages.success',
    'uses' => 'ProcessController@success'
]);
