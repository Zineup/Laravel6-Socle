<?php

/*
 * Frontend Access Controllers
 * All route names are prefixed with 'frontend.crud'.
 */
Route::group(['namespace' => 'CRUD', 'as' => 'crud.'], function () {
    // for city CRUD
    Route::group(['middleware' => 'auth'], function () {
        Route::resource('city', 'CityController');
    });
});
