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
use App\Models\Image;
use App\Models\Post;

class FacebookController extends Controller
{
    //
    protected $client;
    const ACCESS_TOKEN = "EAAEMIFXtj8QBO6poiBuoXNB20ZA6zLEHj8Qr2wr5vkmTDtcoYGZCaBEYDhYIiWQgg8b2oHYTtlJykO9pSD9HtrmZCjxI2GBUT8SHZBf11H8ZAPYVNJDKHtzhQGHlJZBhmXUZBdMNtd55MofwuJ94r7TPE9OmZBwqBU6PZAz8mZCAwVOfLqcnOe6O05ipVkZB2SZBVhRuIAy5DnIZB6jKbTOZBnpyZBbHmMuWDmb3MtZBj05Ht7CB";
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
            $accessToken = Account::where('user_id', Auth::user()->id)->where('platform', 'facebook')->first()->access_token;
            if (!$accessToken) {

            }
        } catch (\Exception $e) {

        }
    }

    public function checkAccessToken()
    {
        Log::info('Checking access token');
        try {
            $response = $this->client->get('/me', self::ACCESS_TOKEN);
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $me = $response->getGraphUser();

        return response()->json([
            'id' => $me->getId(),
            'name' => $me->getName(),
        ], 200);
    }

    public function getListPost()
    {
        Log::info('Get list post');
        try {
            $response = $this->client->get('/me/posts', self::ACCESS_TOKEN);
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $listPost = $response->getGraphList();
        return $listPost;
        // return;
    }

    public function getAttachmentPost($post_id)
    {
        Log::info('Get attachment post: ' . $post_id);
        try {
            $response = $this->client->get("/{$post_id}/attachments", self::ACCESS_TOKEN)->getGraphEdge();
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        try {
            $postAttachment = $response[0];
            return $postAttachment;
        } catch (\Exception $e) {
            return null;
        }


        // return;
    }

    public function getUploadedImagePost($post_id)
    {
        Log::info('Get post uploaded image: ' . $post_id);
        $postAttachment = $this->getAttachmentPost($post_id);
        if ($postAttachment) {
            $images = $postAttachment['media']['image'];
            return $images;
        }
    }

    // public function getPostImpressions($post_id)
    // {
    //     Log::info('Get post impressions: ' . $post_id);
    //     try {
    //         $response = $this->client->get("/{$post_id}/attachments", self::ACCESS_TOKEN)->getGraphEdge();
    //     } catch (FacebookResponseException $e) {
    //         Log::error('Graph returned an error: ' . $e->getMessage());
    //         exit;
    //     } catch (FacebookSDKException $e) {
    //         Log::error('Facebook SDK returned an error: ' . $e->getMessage());
    //         exit;
    //     }
    //     $postAttachment = $response[0];
    //     return $postAttachment;
    // }

    public function getPostInsights($post_id)
    {
        Log::info('Get post insights: ' . $post_id);
        try {
            $response = $this->client->get("/{$post_id}/insights?metric=post_impressions,post_engaged_users,post_reactions_by_type_total", self::ACCESS_TOKEN)->getGraphList();
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $postInsights = $response;
        return $postInsights;
    }

    public function getPostComments($post_id)
    {
        Log::info('Get post comments: ' . $post_id);
        try {
            $response = $this->client->get("/{$post_id}?fields=comments.summary(true)", self::ACCESS_TOKEN)->getDecodedBody();
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $postComments = $response;
        return $postComments;

    }

    public function getPostStatistics($post_id)
    {
        Log::info('Get post statistics: ' . $post_id);
        $postInsights = $this->getPostInsights($post_id);
        $postComments = $this->getPostComments($post_id);
        $post_impressions = 0;
        $post_reactions = 0;
        $post_engaged = 0;
        $post_comments = 0;
        $post_comments = $postComments["comments"]["summary"]["total_count"];
        foreach ($postInsights as $postInsight) {
            $name = $postInsight["name"];
            $values = $postInsight["values"];
            switch ($name) {
                case "post_impressions":

                    $post_impressions += $values[0]['value'];
                    break;
                case "post_reactions_by_type_total":
                    $reactions = $values[0]['value'];
                    foreach ($reactions as $reaction => $count) {
                        $post_reactions += $count;
                    }
                    break;
                case "post_engaged_users":

                    $post_engaged += $values[0]['value'];
                    break;

            }
        }
        $postStatistics = [
            'post_impressions' => $post_impressions,
            'post_engaged' => $post_engaged,
            'post_reactions' => $post_reactions,
            'post_comments' => $post_comments,
        ];
        return $postStatistics;
    }

    public function backupData()
    {
        Log::info('Backup facebook post data');
        $listPost = $this->getListPost();
        if ($listPost) {
            foreach ($listPost as $post) {
                $postStatistics = $this->getPostStatistics($post['id']);
                $postData = array(
                    'post_id' => $post['id'],
                    'content' => nl2br(e($post['message'])),
                    'status' => 'Đã đăng',
                    'total_impressions' => $postStatistics['post_impressions'],
                    'total_engaged' => $postStatistics['post_engaged'],
                    'total_reactions' => $postStatistics['post_reactions'],
                    'total_comment' => $postStatistics['post_comments'],
                    'channel_id' => '2',
                    'user_id' => Auth::user()->id,
                    'platform' => 'facebook',
                    'created_at' => $post['created_time'],
                    'posted_time' => $post['created_time'],
                    'updated_at' => now(),
                    'link' => "https://facebook.com/{$post['id']}"
                );

                Log::info('Save data post to database');
                try {
                    Post::create(
                        $postData
                    );
                } catch (\Exception $e) {
                    Log::error('Save data post to database failed' . "\n" . $e->getMessage());

                }
                $postAttachment = $this->getAttachmentPost($post['id']);
                if (isset($postAttachment)) {
                    $imageSrc = $postAttachment['media']['image']['src'];
                    $images = array(
                        'image_url' => $imageSrc,
                        'post_id' => $post['id']
                    );

                    Log::info('Save data image to database');
                    try {
                        Image::create($images);
                    } catch (\Exception $e) {
                        Log::error('Save data image to database failed' . "\n" . $e->getMessage());
                    }
                }

            }
        }
    }

}