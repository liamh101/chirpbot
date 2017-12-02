<?php

namespace App\Services;

use App\JokePost;
use App\RedditDomainBlacklist;
use App\User;
use App\UserJoke;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RedditService
 * @package App\Services
 */
class RedditService
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var bool
     */
    protected $nsfw;

    /**
     * RedditService constructor.
     * @param string $redditUrl
     */
    public function __construct($redditUrl, $nsfw = false)
    {
        $this->host = $redditUrl;
        $this->nsfw = $nsfw;
    }

    /**
     * Get the current best image from r/funny.
     *
     * @return array|null
     * @throws \Exception
     */
    public function getTopFunnyImage(): ?array
    {
        $data = null;
        $limit = 10;

        $posts = $this->getSubredditData('funny', $limit);

        do {
            foreach ($posts as $post) {
                if ($this->isBlacklistedDomain($post->data->domain)) {
                    continue;
                }

                if ($this->postExists($post->data->url)) {
                    continue;
                }

                if (
                    $post->data->post_hint === 'image' ||
                    $post->data->post_hint === 'link'
                ) {
                    $data = [
                        'title' => $post->data->title,
                        'link' => $post->data->url
                    ];
                    break;
                }
            }

            $limit += 10;
        } while (!$data);

        return $data;
    }

    /**
     * Get a joke for a user
     *
     * @param User $user
     * @return JokePost
     * @throws \Exception
     */
    public function getUserJoke(User $user): JokePost
    {
        $jokes = JokePost::all();

        foreach ($jokes as $joke) {
            if ($this->userSeenJoke($user, $joke)) {
                continue;
            }

            $userJoke = new UserJoke();

            $userJoke->userId = $user->id;
            $userJoke->jokeId = $joke->id;
            $userJoke->save();

            return $joke;
        }

        $post = $this->getTopJokePost();

        $userJoke = new UserJoke();

        $userJoke->userId = $user->id;
        $userJoke->jokeId = $post->id;
        $userJoke->save();

        return $post;
    }

    /**
     * @return JokePost
     * @throws \Exception
     */
    public function getTopJokePost(): JokePost
    {
        $joke = null;
        $limit = 10;

        $posts = $this->getSubredditData('jokes', $limit);

        do {
            foreach ($posts as $post) {

                if ($post->data->stickied) {
                    continue;
                }

                if (!$post->data->domain === 'self.jokes') {
                    continue;
                }

                if ($post->data->link_flair_text === 'Long') {
                    continue;
                }

                if ($this->postExists($post->data->url, JokePost::query())) {
                    continue;
                }

                if (
                    $post->data->title && $post->data->selftext
                ) {

                    $joke = new JokePost;

                    $joke->title = $post->data->title;
                    $joke->punchline = $post->data->selftext;
                    $joke->link = $post->data->url;
                    $joke->save();
                    break;
                }
            }

            $limit += 10;
        } while (!$joke);

        return $joke;
    }

    /**
     * Get posts from reddit
     *
     * @param string $subreddit
     * @param int $count
     * @return array
     * @throws \Exception
     */
    private function getSubredditData(string $subreddit, int $count): array
    {
        $url = $this->host . 'r/' . $subreddit . '.json?count=' . $count;

        $curl = curl_init();

        // Disable SSL verification
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($curl, CURLOPT_URL, $url);

        $apiResponse = curl_exec($curl);

        $response = json_decode($apiResponse);

        //check if non-valid JSON is returned
        if ($error = json_last_error()) {
            throw new \Exception($apiResponse);
        }

        curl_close($curl);

        return $response->data->children;
    }

    /**
     * Is the current domain blacklisted.
     *
     * @param string $domain
     * @return bool
     */
    private function isBlacklistedDomain(string $domain): bool
    {
        return in_array($domain, RedditDomainBlacklist::all());
    }

    /**
     * Does the post currently exist in the database.
     *
     * @param string $postLink
     * @param Builder $query
     * @return bool
     */
    private function postExists(string $postLink, Builder $query): bool
    {
        return !!$query->where('link', $postLink)->first();
    }

    /**
     * Has the user seen the current joke.
     *
     * @param User $user
     * @param JokePost $joke
     * @return bool
     */
    private function userSeenJoke(User $user, JokePost $joke): bool
    {
        return !!UserJoke::where([
                ['userId', '=', $user->id],
                ['jokeId', '=', $joke->id]
            ])->first();
    }
}