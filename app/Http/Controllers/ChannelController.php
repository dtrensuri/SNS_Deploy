<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    //

    public function getPlatformModal()
    {
        return view('modal.selectPlatform');
    }
}