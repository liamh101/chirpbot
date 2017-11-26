<?php

namespace App\Http\Controllers;

use App\Services\FacebookService;
use App\Services\RedditService;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    /**
     * @var FacebookService
     */
    private $facebookService;

    /**
     * @var RedditService
     */
    private $redditService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
        $this->redditService = resolve(RedditService::class);
    }

    public function test()
    {
        $response = $this->facebookService->createNewPost('It works!');

        $graphNode = $response->getGraphNode();

        return 'Posted with id: ' . $graphNode['id'];
    }

    public function uploadPhoto()
    {
        $post = $this->redditService->getTopFunnyImage();
        $response = $this->facebookService->createNewPhotoPost($post['title'], $post['link']);

        $graphNode = $response->getGraphNode();

        return 'Posted with id: ' . $graphNode['id'];
    }
}
