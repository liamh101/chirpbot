<?php

namespace App\Console\Commands;

use App\Services\FacebookService;
use App\Services\RedditService;
use Illuminate\Console\Command;

class facebookPhotoPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:funnyPhotoPost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post the current top post of r/funny to facebook wall.';

    /** @var FacebookService */
    protected $facebookService;

    /** @var RedditService  */
    protected $redditService;

    /**
     * Create a new command instance.
     *
     * @param FacebookService $facebookService
     */
    public function __construct(FacebookService $facebookService)
    {
        parent::__construct();
        $this->facebookService = $facebookService;
        $this->redditService = resolve(RedditService::class);
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handle(): void
    {
        $post = $this->redditService->getTopFunnyImage();
        $this->facebookService->createNewPhotoPost($post['title'], $post['link']);

        $this->info('Funny post has been created');
    }
}
