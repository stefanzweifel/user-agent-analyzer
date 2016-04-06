<?php

Route::get('/', [
    'as' => 'home',
    'uses' => 'HomeController@index'
]);

Route::post('resource', [
    'as' => 'process.store',
    'uses' => 'ProcessController@store'
]);

Route::get('resource/{process}', [
    'as' => 'process.show',
    'uses' => 'ProcessController@show'
]);

Route::patch('resource/{process}/', [
    'as' => 'process.update',
    'uses' => 'ProcessController@update'
]);
