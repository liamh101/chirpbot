<?php

namespace App\Console\Commands;

use App\FacebookAccessTokens;
use App\Services\FacebookService;
use Illuminate\Console\Command;

class facebookTokenGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a page access token for the facebook Graph API';

    /** @var FacebookService  */
    protected $facebookService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FacebookService $facebookService)
    {
        parent::__construct();

        $this->facebookService = $facebookService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handle()
    {
        $token = $this->facebookService->generateAccessToken();

        $facebookTokenEntity = new FacebookAccessTokens();
        $facebookTokenEntity->token = $token;
        return $facebookTokenEntity->save();
    }
}
