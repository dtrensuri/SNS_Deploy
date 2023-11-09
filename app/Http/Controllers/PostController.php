<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Image;
use App\Models\User;

class PostController extends Controller
{
    //
    protected $twitter;
    protected $facebook;

    public function __construct()
    {
        $this->twitter = new TwitterController();
        $this->facebook = new FacebookController();
    }
    public function index(Request $request)
    {

    }

    public function fbGetPostInsightsDB(Request $request)
    {
        $listPost = Post::where('platform', 'facebook')->paginate(5);

        if ($listPost) {
            foreach ($listPost as $index => $post) {
                $listPost[$index]['img'] = Image::where('post_id', $post['post_id'])->first();
            }
            return $listPost;
        }
        return null;
    }

    public function twGetPostInsightsDB(Request $request)
    {
        $listPost = Post::where('platform', 'twitter')->paginate(5);

        if ($listPost) {
            foreach ($listPost as $index => $post) {
                $listPost[$index]['img'] = Image::where('post_id', $post['post_id'])->first();
            }
            return $listPost;
        }
        return null;
    }


    public function getPostInsightsDB(Request $request)
    {
        $listPost = Post::all();
        if ($listPost) {
            foreach ($listPost as $index => $post) {
                $listPost[$index]['img'] = Image::where('post_id', $post['post_id'])->first();
                $listPost[$index]['user'] = User::where('id', $post['user_id'])->select('name')->first()->name;
            }
            return $listPost;
        }
        return null;
    }


    public function viewPostPlatform(Request $request, string $platform)
    {
        $postData = null;
        switch ($platform) {
            case "facebook":
                $postData = $this->fbGetPostInsightsDB($request);
                break;
            case "twitter":
                $postData = $this->twGetPostInsightsDB($request);
                break;
            case "instagram":
                break;
            default:
                break;
        }

        return view('user.post.view', ['postData' => $postData, 'platform' => $platform]);
    }

    public function viewCreatePlatform(Request $request, string $platform = null)
    {
        $postData = null;
        switch ($platform) {
            case null:
                $postData = $this->getPostInsightsDB($request);
                break;
            case "facebook":
                $postData = $this->fbGetPostInsightsDB($request);
                break;
            case "twitter":
                break;
            case "instagram":
                break;
            default:
                break;

        }

        return view('user.post.create', ['postData' => $postData, 'platform' => $platform]);
    }

    public function getCreateModal(Request $request)
    {
        return view('modal.createPost');
    }

    public function getUrl(Request $request)
    {
        $action = $request->input('action');
        $platform = $request->input('platform');
        if ($action == 'create') {
            $url = secure_url(route('user.create-post', ['platform' => $platform]));
        } else {
            $url = secure_url(route('user.view-post', ['platform' => $platform]));
        }

        return response()->json(['url' => $url]);
    }

    function createPost(Request $request)
    {
        if ($request->input('twitter') == 'on') {
            try {
                $response['twitter'] = $this->twitter->createNewTweet($request);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to create post.'], 500);
            }
        }
        if ($request->input('facebook') == 'on') {
            try {
                $response['facebook'] = $this->facebook->createNewPost($request);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to create post.', 'error' => $e->getMessage()], 500);
            }
        }
        if (isset($response)) {
            return response()->json($response);
        }
    }

}