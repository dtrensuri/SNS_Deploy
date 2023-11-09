<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    const ACCESS_TOKEN = "EAAEMIFXtj8QBO87OohQVkmcBu5OJZBaiZCAYABAWq2PwYPytzfkvtEsincIZC3DsXTMgMPprGo9jpRZCct0UlOCZCD2OPQo8lURnJaZB8Mu0RRr4kxZCSyjKsAbOyZCIqnNlTZC0E9jWZAAk2eN5MUnhTEZAi3DWuYG4kKbA8ONDFcJN9eQD2uLctp3ctaVwmynzWZCgospvHMfFSLIiNPMZD";
    public function __construct()
    {
        $this->client = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('FB_GRAPH_VERSION', 'v18.0'),
        ]);
    }

    public function loginCallback(Request $request)
    {
        $helper = $this->client->getRedirectLoginHelper();
        $state = $request->input('state');
        $storedState = session('facebook_state');
        if (!$state || $state !== $storedState) {
            return redirect(secure_url('login'))->with('error', 'CSRF validation failed.');
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (isset($accessToken)) {
            dd($accessToken);
        }
    }

    public function loginFacebook()
    {
        $helper = $this->client->getRedirectLoginHelper();
        $permissions = [];

        $state = csrf_token();

        session(['facebook_state' => $state]);

        $loginUrl = $helper->getLoginUrl('https://dtrensuri-laravel-test-c70aeea3cdb5.herokuapp.com/public/auth/facebook/callback', $permissions, $state);
        return redirect()->away($loginUrl);
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

    public function refreshData(Request $request)
    {
        Log::info('Refresh facebook post data');
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
                    Post::updateOrInsert(
                        ['post_id' => $post['id']],
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
                        Image::updateOrInsert(
                            ['post_id' => $post['id']],
                            $images
                        );
                    } catch (\Exception $e) {
                        Log::error('Save data image to database failed' . "\n" . $e->getMessage());
                    }
                }
            }
        }
    }

    public function createPostWithMedia($content, $tmpFilePath, $accessToken)
    {
        try {
            $response = $this->client->post('/me/photos', [
                'message' => $content,
                'source' => $tmpFilePath,
                'access_token' => $accessToken,
            ]);
            dd($response);
            $postInfo = $response->getDecodedBody();
            return $postInfo;
        } catch (FacebookResponseException $e) {
            Log::error('Facebook API Error: ' . $e->getMessage());
            throw new Exception('Failed to create post with media', 500);
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK Error: ' . $e->getMessage());
            throw new Exception('Failed to create post with media', 500);
        }
    }

    public function createPostWithoutMedia($content)
    {
        Log::info('Create post without media');

        try {
            $response = $this->client->post('/me/feed', [
                'message' => $content,
                'access_token' => self::ACCESS_TOKEN,
            ]);
            $postInfo = $response->getDecodedBody();
            return $postInfo;
        } catch (FacebookResponseException $e) {
            Log::error('Lỗi Facebook API: ' . $e->getMessage());
            return null;
        } catch (FacebookSDKException $e) {
            Log::error('Lỗi Facebook SDK: ' . $e->getMessage());
            return null;
        }
    }

    // public function getPageAccessToken(Request $request){
    //     $response = $this->client->
    // }

    public function createNewPost(Request $request)
    {
        $accessToken = self::ACCESS_TOKEN;
        $newPost = new Post();
        $newPost->user_id = Auth::user()->id;
        $newPost->created_at = now();
        $newPost->channel_id = '1';
        $newPost->platform = 'facebook';
        $title = $request->input('title');
        $description = $request->input('description');

        if (empty($title) && empty($description)) {
            return response()->json(['message' => 'Nội dung post hiện trống.'], Response::HTTP_BAD_REQUEST);
        }

        $content = trim("$title\n$description");

        $newPost->content = $content;
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $tmpFilePath = $file->getRealPath();
                $response = $this->createPostWithMedia($content, $tmpFilePath, $accessToken);

                $imageLink = '';
            } else {
                $response = $this->createPostWithoutMedia($content);

            }
            if (isset($response['id'])) {
                $id_post = $response['id'];
                $newPost->post_id = $id_post;
                $newPost->posted_time = now();
                $newPost->status = 'Đã đăng';
                $newPost->save();
            }

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function autoUpdateFacebookData()
    {
        Log::info('Call API to update Facebook data');
        $posts = Post::all();
        foreach ($posts as $post) {
            // Log::info($post->post_id);
            try {
                $engaged = $this->getTotalEngagedUsers($post->post_id);
                if ($engaged) {
                    $post->total_engaged = $engaged;
                }
            } catch (\Exception $e) {
                Log::error('Lỗi call api getTotalEngagedUsers facebook' . $e->getMessage());
                return true;
            }
            try {
                $impressions = $this->getTotalImpressions($post->post_id);
                if ($impressions) {
                    $post->total_impressions = $impressions;
                }
            } catch (\Exception $e) {
                Log::error('Lỗi call api getTotalImpressions facebook');
                return true;
            }
            try {
                $reactions = $this->getTotalReactions($post->post_id);
                if ($reactions) {
                    $post->total_reactions = $reactions;
                }
            } catch (\Exception $e) {
                Log::error('Lỗi call api getTotalReactions facebook');
                return true;
            }
            $post->save();
        }
        return true;
    }


}