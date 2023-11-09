<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\TwitterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('twitter')->group(function () {
    Route::get('/find-me', [TwitterController::class, 'checkAuthenticate']);
    Route::get('/test-tweet', [TwitterController::class, 'testTweet']);
    Route::get('/get-my-follower', [TwitterController::class, 'getMyFollower']);
});

Route::prefix('facebook')->group(function () {
    Route::post('login-callback', [FacebookController::class, 'loginCallback'])->name('facebook.apiCallbacks');
});

Route::get('/user', [FacebookController::class, 'checkAccessToken']);