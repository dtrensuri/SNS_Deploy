<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PostController;


Route::get('/', function () {
    return redirect(route('user.view-post', ['platform' => 'facebook']));
})->name('index');

Route::name('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('.login_page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::name('user')->middleware('auth')->group(function () {
    Route::get('post/{platform}', [PostController::class, 'viewPostPlatform'])->name('.view-post');
    Route::post('/get-url-platform', [PostController::class, "getUrl"])->name('.get-url-platform');
    Route::get('/logout', [AuthController::class, 'logout'])->name('.logout');

    Route::name('.setting')->prefix('setting')->group(function () {
        Route::get('channel-settings', [SettingController::class, 'createChannelSetting'])->name('.channel');
    });
});
// Route::prefix('callback')->group(function () {
//     Route::get('facebook-login', [FacebookController::class, 'loginCallback'])->name('facebook-login-callback');
// });