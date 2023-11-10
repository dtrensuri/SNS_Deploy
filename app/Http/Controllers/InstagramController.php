<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    //
    protected $client;
    protected $callback;

    protected $state;
    public function __construct()
    {
        $this->client = new Facebook([
            'app_id' => env('IG_APP_ID'),
            'app_secret' => env('IG_APP_SECRET'),
            'default_graph_version' => env('FB_GRAPH_VERSION', 'v18.0'),
        ]);
        $this->callback = env('APP_ENV') == 'production' ? secure_url(route('facebook.apiCallbacks')) : route('facebook.apiCallbacks');
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

    public function loginInstagramAccount()
    {
        $helper = $this->client->getRedirectLoginHelper();
        $helper->getPersistentDataHandler()->set('state', $this->state);
        $permissions = [
            "instagram_basic",
            "instagram_content_publish",
            "instagram_manage_messages",
            "instagram_manage_comments",
            "instagram_manage_insights",
            "instagram_shopping_tag_products",
            "pages_show_list",
            'pages_read_engagement'
        ];
        $loginUrl = $helper->getLoginUrl($this->callback, $permissions);
        return redirect()->away($loginUrl);
    }

}
