<?php

namespace App\Http\Controllers;

use App\Conversations\CreateMessageConversation;
use App\Conversations\HelpConversation;
use App\GenericMessage;
use App\ImageMessage;
use App\Services\RedditService;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Slack\SlackDriver;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BotManController
 * @package App\Http\Controllers
 */
class BotManController extends Controller
{

    /**
     * @var RedditService $redditService
     */
    private $redditService;

    /**
     * BotManController constructor.
     */
    public function __construct()
    {
        $this->redditService = resolve(RedditService::class);
    }

    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * @param BotMan $bot
     */
    public function helpUser(BotMan $bot)
    {
        $bot->startConversation(new HelpConversation());
    }

    /**
     * @param BotMan $bot
     */
    public function createMessage(BotMan $bot)
    {
        $bot->startConversation(new CreateMessageConversation());
    }

    /**
     * @param BotMan $bot
     * @return Response
     */
    public function fallBackMessages(BotMan $bot)
    {
        if ($bot->getDriver() instanceof SlackDriver) {
            return null;
        }

        $messageText = $bot->getMessage()->getText();

        $imageMessage = ImageMessage::where('message', $messageText)->first();

        if ($imageMessage instanceof ImageMessage) {
            $attachment = new Image($imageMessage->image);

            $message = OutgoingMessage::create($imageMessage->response)
                ->withAttachment($attachment);

            $bot->reply($message);
            return $bot->reply($imageMessage->response);
        }

        $generalMessage = GenericMessage::where('message', $messageText)->first();

        if ($generalMessage instanceof GenericMessage) {
            return $bot->reply($generalMessage->response);
        }

        return $bot->reply('I beg your pardon');
    }

    /**
     * @param BotMan $bot
     * @return Response
     */
    public function tellAJoke(BotMan $bot)
    {
        $botUser = $bot->getUser();
        $user = User::where('identifier', $botUser->getId())->first();

        if (!$user) {
            $user = new User();
            $user->identifier = $botUser->getId();
            $user->save();
        }

        $post = $this->redditService->getUserJoke($user);

        $bot->reply($post->title);
        return $bot->reply($post->punchline);
    }
}
