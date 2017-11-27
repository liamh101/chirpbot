<?php

namespace Tests\BotMan;

use Illuminate\Foundation\Inspiring;
use Tests\TestCase;

class BotmanMessageTest extends TestCase
{

    /**
     * Test generic messages creation
     */
    public function testCreateCustomTextMessage()
    {
        $this
            ->bot
            ->receives('Create message')
            ->assertQuestion('what kind of message do you want to create?')
            ->receivesInteractiveMessage('text')
            ->assertReply('Please set message')
            ->receives('This is a test')
            ->assertReply('What response should I give?')
            ->receives('Hello world!')
            ->assertReply('Message has been created!');
    }

    /**
     * Test image message creation
     */
    public function testCreateCustomImageMessage()
    {
        $this
            ->bot
            ->receives('Create message')
            ->assertQuestion('what kind of message do you want to create?')
            ->receivesInteractiveMessage('image')
            ->assertReply('Please set message')
            ->receives('This is an image test')
            ->assertReply('What response should I give?')
            ->receives('Hello world!')
            ->assertReply('What Image should I show?')
            ->receives('http://test.com/image.png')
            ->assertReply('Message has been created!');
    }

    /**
     * Test generic message retrieval works.
     */
    public function testGenericMessage()
    {
        $this
            ->bot
            ->receives('Unit test message')
            ->assertReply('working');
    }

    /**
     * Test image message retrieval works.
     */
    public function testImageMessage()
    {
        $this
            ->bot
            ->receives('Image Unit test')
            ->assertReply('working')
            ->assertReply('working');
    }

    /**
     * Test fallback messages.
     */
    public function testFallbackMessage()
    {
        $this->bot
            ->receives('message doesn\'t exist')
            ->assertReply('I beg your pardon');
    }
}
