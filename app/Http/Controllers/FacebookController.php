<?php

namespace App\Http\Controllers;

use App\Services\FacebookService;
use App\Services\RedditService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FacebookController
 * @package App\Http\Controllers
 */
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

    /**
     * FacebookController constructor.
     * @param FacebookService $facebookService
     */
    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
        $this->redditService = resolve(RedditService::class);
    }

    /**
     * Post reddit's top funny image to the facebook wall
     *
     * @return Response
     * @throws \Exception
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function uploadPhoto(): Response
    {
        $post = $this->redditService->getTopFunnyImage();
        $response = $this->facebookService->createNewPhotoPost($post['title'], $post['link']);

        $graphNode = $response->getGraphNode();

        return Response('Posted with id: ' . $graphNode['id']);
    }
}
