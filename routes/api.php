<?php

use \App\Http\Controllers\CarController As Car;
use \App\Http\Controllers\AuthController As AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' =>'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::prefix('user')->group(function () {
            Route::controller(AuthUser::class)->group(function(){
                Route::post('/register','register');
            });
        });
    });

    Route::group(['prefix' => 'car'], function (){
        Route::controller(Car::class)->group(function (){
            Route::get('/',  'index');
            Route::post('/store','store');
            Route::post('/update/{id}', 'update');
            Route::delete('/destroy/{id}', 'destroy');
            Route::post('/{id}/driver', 'addDriver');
            Route::post('/{id}/remove-driver', 'removeDriver');
        });
    });

});

