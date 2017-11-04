<?php
/**
 * Created by PhpStorm.
 * User: liamh
 * Date: 03/11/2017
 * Time: 18:45
 */

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class HelpConversation extends Conversation
{


    public function askReason() {
        $this->ask('What the fuck do you want?', function (Answer $answer) {
            $this->say('actually, I don\'t fuckin care, piss off');
        });
    }


    /**
     * @return mixed
     */
    public function run()
    {
        return $this->askReason();
    }
}