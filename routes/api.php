<?php

use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);

});


Route::group([
    'middleware' => 'api',
    'prefix' => 'nojwt'
], function ($router) {
    Route::post('test', function() {
        return "sin middleware ni nada";
    });
});


Route::group([
    'middleware' => ['api', 'jwt.auth'],
    'prefix' => 'prueba'
], function ($router) {


    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('get-me', [AuthController::class, 'protectedResource']);

});
