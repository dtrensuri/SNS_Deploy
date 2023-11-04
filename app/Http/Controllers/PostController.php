<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\FacebookController;

class PostController extends Controller
{
    //
    public function index(Request $request)
    {

    }

    public function viewPostPlatform(Request $request, string $platform)
    {
        $fb = new FacebookController();
        $postData = null;
        switch ($platform) {
            case "facebook":
                $postData = $fb->getPostInsightsDB($request);
                break;
            case "twitter":
                break;
            case "instagram":
                break;
            default:
                break;
        }

        return view('user.post.view', ['postData' => $postData, 'platform' => $platform]);
    }

    public function getUrl(Request $request)
    {
        $platform = $request->input('platform');
        $url = route('user.view-post', ['platform' => $platform]);
        return response()->json(['url' => $url]);
    }

}