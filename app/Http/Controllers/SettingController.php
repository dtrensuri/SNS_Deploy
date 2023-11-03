<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('user.setting.channel');
    }

    public function createChannelSetting(Request $request)
    {
        return view('user.setting.channel');
    }
}