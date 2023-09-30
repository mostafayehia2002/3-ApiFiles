<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(ApiController::class)->group(function () {
    Route::post('/store', 'storeFile');
   Route::get('/show', 'getFiles');
  Route::get('/show/{id}', 'searchFiles');
  Route::post('/file/delete/{id}', 'deleteFile');

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
