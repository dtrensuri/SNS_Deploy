<?php

namespace App\Http\Controllers;

use Exception;
use Noweh\TwitterApi\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class TwitterController extends Controller
{


    private $client;
    private $id_user;
    public function __construct()
    {
        $this->id_user = Auth::user()->id;
        $account = Account::where("user_id", $this->id_user)
            ->where('platform', 'twitter')->all();
        $this->client = new Client([
            'account_id' => $account['id_account'],
            'access_token' => $account['access_token'],
            'access_token_secret' => $account['access_secret'],
            'consumer_key' => $account['consumer_key'],
            'consumer_secret' => $account['consumer_secret'],
            'bearer_token' => $account['bearer_token'],
        ]);
    }
    public function createTweets(Request $request)
    {

        $response = $this->client->tweet()->create()
            ->performRequest(
                [
                    'text' => $request->content
                ],
                withHeaders: true
            )
        ;
        return response()->json($response, 200);
    }

    public function deleteTweets(Request $request)
    {
        $tweet_id = $request->query->get('tweet_id');
        $response = $this->client->tweet()->delete($tweet_id);
        return response()->json($response, 200);
    }
    public function checkAuthenticated(Request $request)
    {
        $response = $this->client->userMeLookup()->performRequest();
        return response()->json($response, 200);
    }
    public function fetchTweets(Request $request)
    {
        $response = $this->client->tweet()->fetch(1714224787160207624)->performRequest();
        return response()->json($response, 200);
    }

    public function getFollowersMe()
    {
        $response = $this->client->userFollows()->getFollowers()->performRequest();
        return response()->json($response, 200);
    }

    public function getFollowerUser(Request $request)
    {
        $userId = $request->user->id;
        try {
            $response = $this->client->userFollows()->follow()->performRequest(['target_user_id' => $userId]);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}