<?php

namespace App\Services;

use App\FacebookAccessTokens;
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
     * Generate a new facebook page access token.
     *
     * @return string
     * @throws FacebookSDKException
     */
    public function generateAccessToken(): string
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $response = $this->facebook->get('/'. $pageId . '?fields=access_token');

        $response->decodeBody();
        $data = $response->getDecodedBody();

        return $data['access_token'];
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
        $token = $this->getLatestToken();
        $response = $this->facebook->post('/me/feed', ['message' => $message], $token);

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
        $token = $this->getLatestToken();

        $response = $this->facebook->post('/me/photos', $data, $token);

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

    /**
     * Get the latest user access token
     *
     * @return null|string
     */
    private function getLatestToken(): ?string
    {
        $token = FacebookAccessTokens::orderBy('created_at', 'desc')->first();

        if ($token instanceof FacebookAccessTokens) {
            return $token->token;
        }

        return null;
    }
}