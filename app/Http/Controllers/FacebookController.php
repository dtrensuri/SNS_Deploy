<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use App\Models\Post;
use App\Models\Channel;

class FacebookController extends Controller
{
    //
    protected $client;
    protected $callback;

    protected $state;
    protected $permissions;
    const ACCESS_TOKEN = "EAAEMIFXtj8QBOzvW9i6vimeGBhe6jr1Rf6yfxusvxSFcw7aScVKrr64ugciNfUW4FwFBaJs3nliLyyes6cX2GcPKpCICHe0DoEg3MJOyLjqkyd0EevA11yRPZAxZBwbKKnI6Qtpdo3H0SwnGkgKKWz8y0en6GtckNIpbxn8ldwZC5Q7DhcmoYcVy48UXEZCzR8tzwKTz1oOiYcm3O6KgTZBeUqnN22HZB6T8RvrEDY1HVq5GWfLqQUEpCRyLrfznZBgUwZDZD";
    public function __construct()
    {
        $this->client = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('FB_GRAPH_VERSION', 'v18.0'),
        ]);
        $this->callback = env('APP_ENV') == 'production' ? secure_url(route('facebook.callBacks')) : route('facebook.callBacks');
        $this->permissions = [
            "pages_manage_ads",
            "pages_manage_metadata",
            "pages_read_engagement",
            "pages_read_user_content",
            "pages_manage_posts",
            "pages_manage_engagement",
            "pages_messaging",
            "pages_show_list",
            "read_insights",
            "email",
            "instagram_basic",
            "instagram_content_publish",
            "instagram_manage_messages",
            "instagram_manage_comments",
            "instagram_manage_insights",
            "instagram_shopping_tag_products",
            "publish_to_groups"
        ];
        $this->state = csrf_token();
    }

    /**
     * Xử lý callback từ Facebook sau quá khi đăng nhập bằng Facebook.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginCallback(Request $request)
    {
        Log::info('Facebook Login Callback');
        if (env('APP_ENV') == 'production') {
            $helper = $this->client->getRedirectLoginHelper();
            $pdata = $helper->getPersistentDataHandler();
            $pdata->set('state', $request->get('state'));
            try {
                $accessToken = $helper->getAccessToken();

            } catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

        } else if (env('APP_ENV') == 'local') {
            $accessToken = self::ACCESS_TOKEN;
        }
        if (isset($accessToken)) {
            $this->saveAccessToken($accessToken);
            return redirect(env('APP_ENV') == 'production' ? secure_url(route('user.setting.channel')) : route('user.setting.channel'));
        }
        return redirect(env('APP_ENV') == 'production' ? secure_url(route('user.setting.channel')) : route('user.setting.channel'));
    }

    /**
     * Kiểm tra lại access token xem sử dụng được.
     * @param string $accessToken
     *
     * @return mixed
     */
    public function checkAccessToken($accessToken)
    {
        Log::info('Checking access token');
        try {
            if (env('APP_ENV') == 'production') {
                $response = $this->client->get('/me/accounts', $accessToken->getValue());
            } else if (env('APP_ENV') == 'local') {
                $response = $this->client->get('/me/accounts', $accessToken);
            }
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $accounts = $response->getGraphEdge();
        return $accounts;
    }

    /**
     * Lưu lại mã accessToken facebook sau khi đăng nhập thành công.
     * @param string|mixed $accessToken
     *
     * @return boolean
     */

    public function saveAccessToken($accessToken)
    {
        Log::info('Saving access token');
        try {
            if (env('APP_ENV') == 'production') {
                $response = $this->client->get('/me/accounts', $accessToken->getValue());
            } else if (env('APP_ENV') == 'local') {
                $response = $this->client->get('/me/accounts', $accessToken);
            }
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $accounts = $response->getGraphEdge();
        if (isset($accounts)) {
            foreach ($accounts as $account) {
                Channel::updateOrCreate(
                    ['id_channel' => $account['id']],
                    [
                        'name_channel' => $account['name'],
                        'access_token' => $account['access_token'],
                        'user_id' => Auth::user()->id,
                        'platform' => 'facebook',
                        'updated_at' => now(),
                    ]
                );
            }
        }
        return true;
    }

    public function loginPageAccount()
    {
        $helper = $this->client->getRedirectLoginHelper();
        $helper->getPersistentDataHandler()->set('state', $this->state);
        $permissions = [
            "pages_manage_ads",
            "pages_manage_metadata",
            "pages_read_engagement",
            "pages_read_user_content",
            "pages_manage_posts",
            "pages_manage_engagement",
            "pages_messaging",
            "pages_show_list",
            "read_insights",
        ];
        $loginUrl = $helper->getLoginUrl($this->callback, $permissions);
        return redirect()->away($loginUrl);
    }

    public function getAllAccessToken()
    {
        Log::info('Getting all access tokens');
        try {
            $accessToken = Channel::where('user_id', Auth::user()->id)
                ->where('platform', 'facebook')
                ->get();
            return $accessToken;
        } catch (\Exception $e) {
            Log::info('Error get all access tokens :' . $e->getMessage());
            return null;
        }
    }
    public function getListPost($accessToken = null)
    {
        Log::info('Get list post');
        try {
            $response = $this->client->get('/me/posts');
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        $listPost = $response->getGraphList();
        return $listPost;
    }

    public function getAttachmentPost($post_id, $accessToken = null)
    {
        Log::info('Get attachment post: ' . $post_id);

        try {
            $response = $this->client->get("/{$post_id}/attachments");
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }
        try {
            $postAttachment = $response->getGraphPage()[0];
            return $postAttachment;
        } catch (\Exception $e) {
            return null;
        }
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
            $response = $this->client->get("/{$post_id}/insights?metric=post_impressions,post_engaged_users,post_reactions_by_type_total")->getGraphList();
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
            $response = $this->client->get("/{$post_id}?fields=comments.summary(true)")->getDecodedBody();
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

    public function refreshData()
    {
        Log::info('Refresh facebook post data');
        $tokensData = $this->getAllAccessToken();
        foreach ($tokensData as $tokenData) {
            $accessToken = $tokenData->access_token;
            $this->client->setDefaultAccessToken($accessToken);
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
                        'channel_id' => $tokenData->id,
                        'user_id' => Auth::user()->id,
                        'platform' => 'facebook',
                        'created_at' => $post['created_time'],
                        'posted_time' => $post['created_time'],
                        'updated_at' => now(),
                        'link' => "https://facebook.com/{$post['id']}"
                    );

                    Log::info('Save data post to database');
                    try {
                        Post::updateOrCreate(['post_id' => $post['id']], $postData);
                    } catch (\Exception $e) {
                        Log::error('Save data post to database failed' . "\n" . $e->getMessage());

                    }

                    // $postAttachment = $this->getAttachmentPost($post['id']);
                    // if (isset($postAttachment)) {
                    //     $imageSrc = $postAttachment['media']['image']['src'];
                    //     $images = array(
                    //         'image_url' => $imageSrc,
                    //         'post_id' => $post['id']
                    //     );

                    //     Log::info('Save data image to database');
                    //     try {
                    //         Media::create($images);
                    //     } catch (\Exception $e) {
                    //         Log::error('Save data image to database failed' . "\n" . $e->getMessage());
                    //     }
                    // }

                }
            }

        }
        Log::info('Refresh data successfully');
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

    // public function createPostWithoutMedia($content, $accessToken)
    // {
    //     Log::info('Create post without media');

    //     try {

    //         $response = $this->client->post('/me/feed', [
    //             'message' => $content,
    //             'access_token' => $accessToken,
    //         ]);
    //         $postInfo = $response->getDecodedBody();
    //         return $postInfo;
    //     } catch (FacebookResponseException $e) {
    //         Log::error('Lỗi Facebook API: ' . $e->getMessage());
    //         return null;
    //     } catch (FacebookSDKException $e) {
    //         Log::error('Lỗi Facebook SDK: ' . $e->getMessage());
    //         return null;
    //     }
    // }

    // public function getPageAccessToken(Request $request){
    //     $response = $this->client->
    // }

    public function createPostWithoutMedia($content, $accessToken)
    {
        Log::info('Create facebook post without media');
        try {
            $response = $this->client->post('/me/feed', [
                'message' => $content,
                'access_token' => $accessToken,
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

    // public function createNewPost(Request $request)
    // {
    //     $accessToken = self::ACCESS_TOKEN;
    //     $newPost = new Post();
    //     $newPost->user_id = Auth::user()->id;
    //     $newPost->created_at = now();
    //     $newPost->channel_id = '1';
    //     $newPost->platform = 'facebook';
    //     $title = $request->input('title');
    //     $description = $request->input('description');

    //     if (empty($title) && empty($description)) {
    //         return response()->json(['message' => 'Nội dung post hiện trống.'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $content = trim("$title\n$description");

    //     $newPost->content = $content;
    //     try {
    //         if ($request->hasFile('image')) {
    //             $file = $request->file('image');
    //             $tmpFilePath = $file->getRealPath();
    //             $response = $this->createPostWithMedia($content, $tmpFilePath, $accessToken);

    //             $imageLink = '';
    //         } else {
    //             $response = $this->createPostWithoutMedia($content);

    //         }
    //         if (isset($response['id'])) {
    //             $id_post = $response['id'];
    //             $newPost->post_id = $id_post;
    //             $newPost->posted_time = now();
    //             $newPost->status = 'Đã đăng';
    //             $newPost->save();
    //         }

    //         return response()->json($response, Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::error($e->getMessage());
    //         return response()->json(['message' => 'An error occurred.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function userLookup($id, $accessToken)
    {
        Log::info('Lookup facebook id: ' . $id);
        try {
            $response = $this->client->get(
                $id,
                $accessToken
            );
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
