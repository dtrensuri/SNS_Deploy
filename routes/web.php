<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\ChannelController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return redirect(env('APP_ENV') == 'production' ? secure_url(route('user.view-post', ['platform' => 'facebook'])) : route('user.view-post', ['platform' => 'facebook']));
})->name('index');

Route::name('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('.login_page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::name('user')->middleware('auth')->group(function () {
    Route::get('post/{platform}', [PostController::class, 'viewPostPlatform'])->name('.view-post');
    Route::get('create/{platform?}', [PostController::class, 'viewCreatePlatform'])->name('.create-post');
    Route::post('/create-post', [PostController::class, "createPost"])->name('.handle-create-post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('.logout');

    Route::name('.setting')->prefix('setting')->group(function () {
        Route::get('channel-settings', [SettingController::class, 'createChannelSetting'])->name('.channel');
    });
    Route::get('/fb-backup', [FacebookController::class, 'backupData']);
});


Route::middleware('auth')->group(function () {
    Route::get('get-create-modal', [PostController::class, 'getCreateModal'])->name('get-create-modal');
    Route::get('get-platform-modal', [ChannelController::class, 'getPlatformModal'])->name('get-platform-modal');
    Route::post('/get-url-platform', [PostController::class, "getUrl"])->name('get-url-platform');
    Route::name('channel')->group(function () {
        Route::get('all-channels', [ChannelController::class, 'getAllChannels'])->name('.all');
        Route::get('added-channel', [ChannelController::class, 'renderTableAddedChannel'])->name('.added');
    });

    Route::prefix('auth')->group(function () {
        Route::prefix('facebook')->group(function () {
            Route::get('user-account', [FacebookController::class, 'loginUserAccount'])->name('fb.user_account');
            Route::get('page-account', [FacebookController::class, 'loginPageAccount'])->name('fb.pages_account');
            // Route::get('instagram-account', [FacebookController::class, 'loginInstagramAccount'])->name('fb.instagram_account');
            Route::get('accessToken', [FacebookController::class, 'getFbAccessToken'])->name('fb.accessToken');
            Route::get('login-callback', [FacebookController::class, 'loginCallback'])->name('facebook.callBacks');
            Route::get('login', [FacebookController::class, 'loginFacebook'])->name('fb.login');
        });
    });
});

Route::get('chinh-sach-rieng-tu', function () {
    return "Chinh sach rieng tu FB";
})->name('fb.chinh-sach');