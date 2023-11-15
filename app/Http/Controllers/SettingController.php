<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\FacebookController;

class SettingController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('user.setting.channel');
    }

    public function createChannelSetting(Request $request)
    {
        $fb = new FacebookController;
        $accessToken = $request->session()->get('access_token');
        $action = $request->session()->get('action');
        if (isset($accessToken) && $accessToken != null) {
            $channels = $fb->getPageAccount($accessToken);
            return view('user.setting.channel', ['channels' => $channels]);
            // dd($channel);
            // switch ($action) {
            //     case 'add-page':
            //         $channel = $fb->getPageAccount($accessToken);
            //         dd($channel);
            //         break;
            //     case 'add-ig-business':
            //         break;
            // }
        }
        return view('user.setting.channel', ['access_token' => $accessToken, 'action' => $action]);
    }
}