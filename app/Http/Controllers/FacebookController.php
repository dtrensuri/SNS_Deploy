<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FacebookController extends Controller
{
    //
    protected $client;
    public function __construct()
    {
        $this->client = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('FB_GRAPH_VERSION'),
        ]);
    }

    public function loginCallback(Request $request)
    {

    }
    public function loginFacebook()
    {
        $helper = $this->client->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes'];
        $loginUrl = $helper->getLoginUrl(route('facebook-login-callback'), $permissions);
        echo $loginUrl;
    }

    public function getAccessTokens()
    {
        Log::info('Get AccessTokens from Database');
        try {
            $accessToken = Account::where('user_id', Auth::user()->id)->first()->access_token;
            if (!$accessToken) {

            }
        } catch (\Exception $e) {

        }
    }

    public function checkAccessToken()
    {
        Log::info('Checking access token');
        try {
            $response = $this->client->get('/me', '');
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $me = $response->getGraphUser();
        return response()->json($me, 200);
    }
}
