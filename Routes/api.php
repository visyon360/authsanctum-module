<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\AuthSanctum\Http\Controllers\AuthSanctumController;

/*
|--------------------------------------------------------------------------
| API Authenthication Routes
| Base Endpoint: domain/api/auth/*
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'auth'], function ()
{
    Route::post('register', [
        'as' => 'api.sanctum.register',
        'uses' => 'AuthSanctumController@register'
    ]);

    Route::post('login', [
        'uses' => 'AuthSanctumController@login',
        'as' => 'api.sanctum.login']);

    Route::post('logout', [
        'uses' => 'AuthSanctumController@logout',
        'as' => 'api.sanctum.logout'
    ])->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Authentichated Routes
| Base Endpoint: domain/api/*
|--------------------------------------------------------------------------
|
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return auth()->user();
    });
});
