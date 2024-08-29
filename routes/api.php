<?php

use App\Http\Controllers\PythonController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
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
//Route::get('/forex', [ApiController::class, 'index'])->name('api.forex');
Route::middleware('auth:sanctum')->get('/chat-gpt/{id}',  [ApiController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
Route::middleware('auth:sanctum')->get('/get/forex',  [ApiController::class, 'getForex'])->withoutMiddleware(['auth:sanctum']);
Route::middleware('auth:sanctum')->get('/post/forex',  [ApiController::class, 'postForex'])->withoutMiddleware(['auth:sanctum']);
Route::middleware('auth:sanctum')->post('/custom/chat/python',  [PythonController::class, 'runScript'])->withoutMiddleware(['auth:sanctum']);
//Route::get('/forex', 'ApiController@index')->name('api.forex');
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
