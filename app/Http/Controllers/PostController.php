<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Media;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;

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

        // if ($listPost) {
        //     foreach ($listPost as $index => $post) {
        //         $listPost[$index]['img'] = Media::where('post_id', $post['post_id'])->first();
        //     }
        //     return $listPost;
        // }
        return $listPost;
    }

    public function twGetPostInsightsDB(Request $request)
    {
        $listPost = Post::where('platform', 'twitter')->paginate(5);

        // if ($listPost) {
        //     foreach ($listPost as $index => $post) {
        //         $listPost[$index]['img'] = Media::where('post_id', $post['post_id'])->first();
        //     }
        //     return $listPost;
        // }
        // return null;
        return $listPost;
    }


    public function getPostInsightsDB(Request $request)
    {
        $listPost = Post::paginate(5);
        // if ($listPost) {
        //     foreach ($listPost as $index => $post) {
        //         $listPost[$index]['img'] = Media::where('post_id', $post['post_id'])->first();
        //         $listPost[$index]['user'] = User::where('id', $post['user_id'])->select('name')->first()->name;
        //     }
        //     return $listPost;
        // }
        // return null;
        return $listPost;
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

        switch ($platform) {
            case "facebook":
                $postData = $this->fbGetPostInsightsDB($request);
                break;
            case "twitter":
                break;
            case "instagram":
                break;
            default:
                $postData = $this->getPostInsightsDB($request);
                break;

        }

        return view('user.post.create', ['postData' => $postData, 'platform' => $platform]);
    }

    public function getCreateModal(Request $request)
    {
        $all_channels = Channel::all();
        return view('modal.newPost', ['all_channels' => $all_channels]);
    }

    public function getCreateCardBody(Request $request)
    {
        $option = $request->get('option');
        switch ($option) {
            case "image":
                $view = view('card.imagePost');
                break;
            case "video":
                $view = view('card.imagePost');
                // $view = view('card.videoPost');
                break;
            case "album":
                $view = view('card.imagePost');
                // $view = view('card.albumPost');
                break;
            case "link":
                $view = view('card.imagePost');
                // $view = view('card.linkPost');
                break;
            case "story":
                $view = view('card.imagePost');
                // $view = view('card.storyPost');
                break;
            case "real":
                $view = view('card.imagePost');
                // $view = view('card.realPost');
                break;
            case 'text':
                // $view = view('card.imagePost');
                $view = view('card.textPost');
                break;
            default:
                $view = view('card.imagePost');
                // $view = view('card.defaultPost');
                break;
        }
        return $view;
    }


    public function getUrl(Request $request)
    {
        $action = $request->input('action');
        $platform = $request->input('platform');
        if ($action == 'create') {
            $url = env('APP_ENV') == 'production' ? secure_url(route('user.create-post', ['platform' => $platform])) : route('user.create-post', ['platform' => $platform]);
        } else {
            $url = env('APP_ENV') == 'production' ? secure_url(route('user.view-post', ['platform' => $platform])) : route('user.view-post', ['platform' => $platform]);
        }
        return response()->json(['url' => $url]);
    }

    public function createTextPost(Request $request, Channel $channel)
    {
        $newPost = new Post();
        $newPost->user_id = Auth::user()->id;
        $newPost->created_at = now();
        $newPost->channel_id = $channel->id;
        $newPost->platform = $channel->platform;

        $title = $request->input('title');
        $description = $request->input('description');

        if (empty($title) && empty($description)) {
            return response()->json(['message' => 'Nội dung post hiện trống.'], \Response::HTTP_BAD_REQUEST);
        }

        $content = trim("$title\n$description");

        switch ($channel->platform) {
            case 'facebook':
                $fb = new FacebookController();
                $fbPost = $fb->createPostWithoutMedia($content, $channel->access_token);
                if ($fbPost) {
                    $newPost->postId = $fbPost['id'];
                    $postInfo = $fb->userLookup($newPost->postId, $channel->access_token);
                    $newPost->posted_time = $postInfo['created_time'];
                    $newPost->status = "Đã đăng";
                    $newPost->save();
                }
                break;
            case 'twitter':
                break;
            case 'instagram':
                break;
        }

        return redirect()->back();
    }

    function createPost(Request $request)
    {
        $all_channels = Channel::all();
        foreach ($all_channels as $channel) {
            $input_name = "id_channel_" . $channel->id;
            if ($request->input($input_name) == 'on') {
                switch ($request->input('typePost')) {
                    case 'text':
                        $this->createTextPost($request, $channel);
                        break;
                    case 'image':
                        break;
                    case 'video':
                        break;
                    case 'link':
                        break;
                    case 'reel':
                        break;
                    case 'story':
                        break;
                    default:
                        break;
                }
            }
        }
    }

}