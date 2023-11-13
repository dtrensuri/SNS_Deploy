<?php

namespace App\Http\Controllers;

use Exception;
use Noweh\TwitterApi\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TwitterController extends Controller
{

    private $client;
    private $id_user;

    public function __construct()
    {
        $this->client = new Client([
            'account_id' => '',
            'access_token' => '1457738824378118144-640SotydewNkRGw58YE6ZuTYWKKBBo',
            'access_token_secret' => 'suBbigD2VInKGNrh98R2ViN8jYcWYojG2G5PYmG3fgD7L',
            'consumer_key' => '1hV5uZjXQo14u6znXedxav0Hk',
            'consumer_secret' => 'qOmSNNvs5KGZGSSGL0P7lQoBrK1n7M16ouVutg9yvCaBukLMZX',
            'bearer_token' => 'AAAAAAAAAAAAAAAAAAAAAI34YAEAAAAAy8bhZY1kJeC%2B0UiRqY%2B3o%2F777tU%3DrAT6PQmHvjWvPg58MGU6WroVOuivKU5j38nT5swIAttqCEYAZp
            ',
        ]);
    }
    public function createNewTweet(Request $request)
    {
        $newTweet = new Post();
        // $newImage = new Image();

        $newTweet->user_id = Auth::user()->id;
        $newTweet->created_at = now();
        $newTweet->channel_id = '1';
        $newTweet->platform = 'twitter';

        $title = $request->input('title');
        $description = $request->input('description');



        if (empty($title) && empty($description)) {
            return response()->json(['message' => 'Nội dung post hiện trống.'], Response::HTTP_BAD_REQUEST);
        }

        $content = trim("$title\n$description");

        $newTweet->content = $content;
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_data = base64_encode(file_get_contents($file));
                $media_info = $this->uploadMedia($file_data);
                $response = $this->createTweetWithMedia($content, $media_info);
            } else {
                $response = $this->createTweetWithoutMedia($content);
            }

            if ($response->data->id) {
                $id_post = $response->data->id;
                $text = $response->data->text;
                $imageLink = $this->extractImageLinkFromText($text);
                $newTweet->post_id = $id_post;
                $newTweet->posted_time = now();
                $newTweet->status = 'Ok';
                if ($imageLink) {
                    $newTweet->link = $imageLink;
                    // $newImage->post_id = $id_post;
                    // $newImage->image_url = $imageLink;
                    // $newImage->created_at = now();
                    // $newImage->updated_at = now();
                    // $newImage->save();
                } else {
                    $newTweet->link = 'https://twitter.com/o_thanh24509/status' . $id_post;
                }
                $newTweet->save();
            }


            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    function extractImageLinkFromText($text)
    {
        $pattern = '/https:\/\/t\.co\/\w+/';
        if (preg_match($pattern, $text, $matches)) {
            return $matches[0];
        }

        return null;
    }


    private function uploadMedia($file_data)
    {
        try {
            return $this->client->uploadMedia()->upload($file_data);
        } catch (\Exception $e) {
            throw new \Exception('Upload file fail.');
        }
    }

    private function createTweetWithMedia($content, $media_info)
    {

        return $this->client->tweet()->create()
            ->performRequest([
                'text' => $content,
                'media' => [
                    'media_ids' => [(string) $media_info['media_id']],
                ],
            ]);

    }

    private function createTweetWithoutMedia($content)
    {
        return $this->client->tweet()->create()
            ->performRequest(['text' => $content]);
    }


    public function testTweet(Request $request)
    {
        $date = new \DateTime('NOW');
        try {
            $response = $this->client->tweet()->create()
                ->performRequest(
                    [
                        'text' => 'Test Tweet... ' . $date->format(\DateTimeInterface::ATOM)
                    ]
                );
            return response()->json($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkAuthenticate(Request $request)
    {
        $response = $this->client->userMeLookup()->performRequest();
        return response()->json($response);
    }

    public function getMyFollower(Request $request)
    {
        try {
            $response = $this->client->userFollows()->getFollowing()->performRequest();
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }
}