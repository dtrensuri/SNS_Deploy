<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FacebookController;


Route::get('/', function () {
    return redirect(route('user.view'));
})->name('index');

Route::name('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('.login_page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::name('user')->middleware('auth')->group(function () {
    Route::prefix('post')->group(function () {
        Route::get('view', function () {
            return view('user.post.view');
        })->name('.view');

        Route::get('create', function () {
            return view('user.post.create');
        })->name('.create');
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('.logout');

    Route::name('.setting')->prefix('setting')->group(function () {
        Route::get('channel-settings', [SettingController::class, 'createChannelSetting'])->name('.channel');
    });

    Route::get('fb-access', [FacebookController::class, 'OAUth2Client']);
    Route::get('fb-post', [FacebookController::class, 'showListPost']);
    Route::get('autoUpdate', [FacebookController::class, 'autoUpdateFacebookData']);
    Route::get('fb-login', [FacebookController::class, 'loginFacebook']);
});
Route::prefix('callback')->group(function () {
    Route::get('facebook-login', [FacebookController::class, 'loginCallback'])->name('facebook-login-callback');
});
