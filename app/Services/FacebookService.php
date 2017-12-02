<?php

namespace App\Services;

use App\PhotoPost;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookResponse;

/**
 * Class FacebookService
 * @package App\Services
 */
class FacebookService
{
    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * FacebookService constructor.
     */
    public function __construct()
    {
        $this->facebook = resolve(Facebook::class);
    }

    /**
     * Mae a message post to the facebook wall.
     *
     * @param string $message
     * @return FacebookResponse
     * @throws FacebookSDKException
     */
    public function createNewPost(string $message): FacebookResponse
    {
        $response = $this->facebook->post('/me/feed', ['message' => $message]);

        return $response;
    }

    /**
     * Make a photo post to the facebook wall.
     *
     * @param string $title
     * @param string $url
     * @return \Facebook\FacebookResponse
     * @throws FacebookSDKException
     */
    public function createNewPhotoPost(string $title, string $url): FacebookResponse
    {
        $data = ['message' => $title, 'source' => $this->facebook->fileToUpload($url)];

        $response = $this->facebook->post('/me/photos', $data);

        $this->saveImagePost($title, $url);

        return $response;
    }

    /**
     * Save photo post to the database
     *
     * @param string $title
     * @param string $link
     */
    private function saveImagePost(string $title, string $link)
    {
        $photoPost = new PhotoPost();

        $photoPost->title = $title;
        $photoPost->link = $link;

        $photoPost->save();
    }
}