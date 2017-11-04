<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('Help!', BotManController::class.'@helpUser');

$botman->hears('Create message', BotManController::class.'@createMessage');

$botman->hears('Tony', function ($bot) {
    $attachment = new Image('https://i.imgur.com/vXlh4ir.jpg');

    $message = OutgoingMessage::create('Do you mean this blert?')
        ->withAttachment($attachment);

    $bot->reply($message);
});

$botman->hears('Keith', function ($bot) {
    $attachment = new Image('https://falsefabs.files.wordpress.com/2013/09/dave-myers.jpg');

    $message = OutgoingMessage::create('Do you mean this blert?')
        ->withAttachment($attachment);

    $bot->reply($message);
    $bot->reply('Do you mean this blert?');
});

$botman->fallback(BotManController::class.'@fallBackMessages');