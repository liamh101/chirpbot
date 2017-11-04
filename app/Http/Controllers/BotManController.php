<?php

namespace App\Http\Controllers;

use App\Conversations\CreateMessageConversation;
use App\Conversations\HelpConversation;
use App\GenericMessage;
use App\ImageMessage;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
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
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    public function helpUser(BotMan $bot)
    {
        $bot->startConversation(new HelpConversation());
    }

    public function createMessage(BotMan $bot)
    {
        $bot->startConversation(new CreateMessageConversation());
    }

    public function fallBackMessages(BotMan $bot)
    {
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

        return $bot->reply('You fuckin what m8?!');
    }
}
