<?php
/**
 * Created by PhpStorm.
 * User: liamh
 * Date: 03/11/2017
 * Time: 18:58
 */

namespace App\Conversations;


use App\GenericMessage;
use App\ImageMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class CreateMessageConversation extends Conversation
{

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $response;


    public function askType()
    {
        $question = Question::create("what kind of message do you want to create?")
            ->fallback('What the fuck you sayin?')
            ->callbackId('ask_type')
            ->addButtons([
                Button::create('Create generic message')->value('text'),
                Button::create('Create image message')->value('image'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'text') {
                    return $this->createGenericMessage();
                }

                return $this->askForImageMessage();
            }

            return $this->run();
        });
    }

    public function createGenericMessage()
    {
        $this->ask('Please set message', function (Answer $answer) {
            $this->message = $answer->getText();

            $this->ask('What response should I give?', function (Answer $answer) {
                $genericMessage = new GenericMessage();

                $genericMessage->message = $this->message;
                $genericMessage->response = $answer->getText();
                $genericMessage->save();

                return $this->say('Message has been created!');
            });
        });
    }

    public function askForImageMessage()
    {
        $this->ask('Please set message', function (Answer $answer) {
            $this->message = $answer->getText();

            $this->ask('What response should I give?', function (Answer $answer) {
                $this->response = $answer->getText();

                $this->ask('What Image should I show?', function (Answer $answer) {
                    $imageMessage = new ImageMessage();

                    $imageMessage->message = $this->message;
                    $imageMessage->response = $this->response;
                    $imageMessage->image = $answer->getText();
                    $imageMessage->save();

                    return $this->say('Message has been created!');
                });
            });
        });
    }

    /**
     * @return mixed
     */
    public function run()
    {
        return $this->askType();
    }
}