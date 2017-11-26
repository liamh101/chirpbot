<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = resolve('botman');

$dialogflow = \BotMan\BotMan\Middleware\ApiAi::create(env('DIALOGFLOW_TOKEN'))->listenForAction();

$botman->middleware->received($dialogflow);

$botman->hears('joke', BotManController::class.'@tellAJoke')->middleware($dialogflow);

$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('Help!', BotManController::class.'@helpUser');

$botman->hears('Create message', BotManController::class.'@createMessage');

$botman->fallback(BotManController::class.'@fallBackMessages');