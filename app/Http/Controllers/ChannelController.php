<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;


class ChannelController extends Controller
{
    //

    public function getPlatformModal()
    {
        return view('modal.selectPlatform');
    }

    public function renderTableAddedChannel()
    {
        return $this->getAllChannels();
    }

    public function getAllChannels()
    {
        $channels = Channel::all();
        if ($channels) {
            return view('table.addedChannel', ['channels' => $channels]);
        }
    }

    public function facebookLogin()
    {

    }

}