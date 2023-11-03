<?php

// namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Facebook\Facebook;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\Image;
use Illuminate\Support\Facades\Log;


class FacebookController extends Controller
{


    const GRAPH_VERSION = 'v18.0';
    const DEFAULT_ACCESS_TOKEN = '';
    private $fb;

    private $id_user;
    protected $version;
    protected $access_token;
    protected $app_id;
    protected $app_secret;
    public function __construct()
    {
        // $this->id_user = Auth::user()->id;
        // $fb_account = Account::where('user_id', $this->id_user)
        //     ->where('platform', 'facebook')->get();
        // $this->fb = new Facebook([
        //     'app_id' => env('FB_APP_ID'),
        //     'app_secret' => env('FB_APP_SECRET'),
        //     'default_graph_version' => self::GRAPH_VERSION
        // ]);

        // $this->fb->setDefaultAccessToken(self::DEFAULT_ACCESS_TOKEN);
    }



    public function getAccessToken()
    {
        // $account = Account::where('user_id', Auth::user()->id)
        //     ->where('platform', "facebook")
        //     ->first();

        // return $account['access_token'];
        return 'EAAEMIFXtj8QBO7ZC3YF4CvsxH0yS7uhE8hfPVf3RFaOx3baYHPOzR6EhI2kmIIfGUVuP8WBLgRcUPYg3ydvZAZBOklYLfrI9J3qZCb9wFt3fbbUopZCGR5BAJnaG1zZAPMHyZAY0ZAZA2tEbxi1vhrLhKnxgZA7LIJVfJBvAZCPUdGLIZAubB06GVWURNpZBMcmDK0RZCbo4TZCm9j3TMFWwxym7c1ldWAEHKwmhOls5sUB6OoZD';
    }

    public function OAUth2Client(Request $request)
    {
        // dd($this->getAccessToken());
        $accessToken = $this->getAccessToken();
        // $fields = 'id,name';
        $app_id = env('FB_APP_ID');
        $app_secret = env('FB_APP_SECRET');
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/me";
        $access_token_url = "https://graph.facebook.com/{$version}/oauth/access_token?client_id={$app_id}&client_secret={$app_secret}&grant_type=client_credentials";

        $response = file_get_contents($access_token_url);
        // dd($response);
        $data = json_decode($response);

        if (!empty($data) && isset($data->access_token)) {
            // $app_access_token = $data->access_token;

            // Sử dụng app_access_token để gọi API
            $url = $api_url . '?fields=' . '&access_token=' . $accessToken;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($result);
            dd($data);
        } else {
            echo 'Không thể tạo mã truy cập ứng dụng.';
        }

    }


    public function getUserInfo(Request $request)
    {
        // try {
        //     Http::get()
        //     $response = $this->fb->get('/me');
        //     return response()->json($response);
        // } catch (\Exception $e) {
        //     return response()->json($e->getMessage());
        // }


    }

    /**
     * Chức năng tạo mới 1 bài post
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createFeed(Request $request)
    {

    }

    /**
     * Chức năng tạo xóa 1 bài post
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deletePost(Request $request)
    {

    }

    public function lookOnePost(Request $request)
    {

    }


    public function getPostAttachments($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/attachments";
        $url = $api_url . '?access_token=' . $accessToken;
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json()['data'];
            return $data;
        }
        return null;
    }

    public function getPostLikes($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/likes";
        $url = $api_url . '?access_token=' . $accessToken;
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json()['data'];
            $numLikes = count($data);
            return array('num_likes' => $numLikes);
        }

        return array('num_likes' => 0);
    }


    public function getPostComments($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/comments";
        $url = $api_url . '?access_token=' . $accessToken;
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json()['data'];
            $numComment = count($data);
            return array('num_comments' => $numComment);
        }
        return array('num_comments' => 0);
    }

    public function getPostShares($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/shares";
        $url = $api_url . '?access_token=' . $accessToken;
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json()['data'];
            $numShare = count($data);
            return array('num_shares' => $numShare);
        }
        return array('num_shares' => 0);
    }

    public function getPostViews($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/views";
        $url = $api_url . '?access_token=' . $accessToken;
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json()['data'];
            $numView = count($data);
            return array('num_views' => $numView);
        }
        return array('num_views' => 0);
    }



    public function getListPost()
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/me/posts";

        $url = $api_url . '?fields=id,message,created_time&access_token=' . $accessToken;

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response['data'];
            return $data;
        }
        return null;
    }


    public function getTotalImpressions($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/insights/post_impressions?access_token=" . $accessToken;
        $response = Http::get($api_url);
        if ($response->successful()) {
            $data = $response->json()['data'][0];
            $totalImpressions = $data['values'][0]['value'];
            return $totalImpressions;
        }
        return null;
    }
    public function getTotalReactions($post_id)
    {
        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/insights/post_reactions_by_type_total?access_token=" . $accessToken;
        $response = Http::get($api_url);
        if ($response->successful()) {
            $data = $response->json()['data'][0];
            $totalReactions = array_sum($data['values'][0]['value']);

            return $totalReactions;

        }
        return null;
    }

    public function getTotalEngagedUsers($post_id)
    {

        $accessToken = $this->getAccessToken();
        $version = self::GRAPH_VERSION;
        Log::info($post_id);
        $api_url = "https://graph.facebook.com/{$version}/{$post_id}/insights/post_engaged_users?access_token=" . $accessToken;
        Log::info('call API ' . $api_url);
        $response = Http::get($api_url);
        if ($response->successful()) {
            $data = $response->json()['data'][0];
            $totalEngaged = $data['values'][0]['value'];
            return $totalEngaged;
        }
        return null;
    }

    // public function getPostDetail($post)
    // {
    //     $postDetail = array('post' => $post);
    //     $attachment = $this->getPostAttachments($post['id']);
    //     $engaged = $this->getTotalEngagedUsers($post['id']);
    //     $impressions = $this->getTotalImpressions($post['id']);
    //     $reaction = $this->getTotalReactions($post['id']);
    //     if ($attachment) {
    //         $postDetail['attachment'] = $attachment;
    //     }
    //     if ($engaged) {
    //         $postDetail['engaged'] = $engaged;
    //     }
    //     if ($impressions) {
    //         $postDetail['impressions'] = $impressions;
    //     }
    //     if ($reaction) {
    //         $postDetail['reaction'] = $reaction;
    //     }

    //     return $postDetail;
    // }



    public function getPostInfo($post)
    {
        Log::info('Get post information');
        $postInfo = array('post' => $post);
        $attachment = $this->getPostAttachments($post['id']);
        if ($attachment) {
            $postInfo['attachment'] = $attachment[0];
        }
        return $postInfo;
    }

    public function showListPost(Request $request)
    {
        Log::info('Show List Post');
        $listPost = Post::paginate(5);
        if ($listPost) {
            foreach ($listPost as $index => $post) {
                $listPost[$index]['img'] = Image::where('post_id', $post['post_id'])->first();
            }

            return view('user.post.tableView.facebookPost', ['data' => $listPost]);

        }
    }


    // public function getListPostFromDatabase()
    // {
    //     Log::info('Show List Post');
    //     $listPost = Post::paginate(5);
    //     if ($listPost) {
    //         foreach ($listPost as $index => $post) {
    //             $listPost[$index]['img'] = Image::where('post_id', $post['post_id'])->first();
    //         }
    //         return response()->json($listPost);
    //     }
    // }

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

    // public function savePostData(Request $request)
    // {
    //     $listPost = $this->getListPost();
    //     if ($listPost) {
    //         foreach ($listPost as $post) {
    //             $portInfo = $this->getPostDetail($post);
    //             $postData = array(
    //                 'post_id' => $post['id'],
    //                 'content' => nl2br(e($post['message'])),
    //                 'status' => 'Ok',
    //                 'total_impressions' => isset($portInfo['impressions']) ? $portInfo['impressions'] : '0',
    //                 'total_engaged' => isset($portInfo['engaged']) ? $portInfo['engaged'] : '0',
    //                 'total_reactions' => isset($portInfo['reaction']) ? $portInfo['reaction'] : '0',
    //                 'total_shares' => isset($portInfo['shares']) ? $portInfo['shares'] : '0',
    //                 'user_id' => Auth::user()->id,
    //                 'platform' => 'facebook',
    //                 'created_at' => $post['created_time'],
    //                 'updated_at' => now(),
    //             );
    //             // Post::create(
    //             //     $postData
    //             // );
    //             if (isset($portInfo['attachment'])) {
    //                 $imgData = $portInfo['attachment'][0];
    //                 $images = array(
    //                     'image_url' => $imgData['media']['image']['src'],
    //                     'post_id' => $post['id']
    //                 );
    //                 Image::create($images);
    //             }
    //         }
    //     }
    //     dd(Image::all());
    // }


    public function loginWithFacebook(Request $request)
    {

    }

    public function callBack(Request $request)
    {

    }
}