<?php

namespace NotificationChannels\Trello\Test;

use DateTime;
use Illuminate\Support\Arr;
use NotificationChannels\Trello\TrelloMessage;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\Trello\TrelloMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new TrelloMessage();
    }

    /** @test */
    public function it_accepts_a_name_when_constructing_a_message()
    {
        $message = new TrelloMessage('Name');

        $this->assertEquals('Name', Arr::get($message->toArray(), 'name'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TrelloMessage::create('Name');

        $this->assertEquals('Name', Arr::get($message->toArray(), 'name'));
    }

    /** @test */
    public function it_can_set_the_name()
    {
        $this->message->name('CardName');

        $this->assertEquals('CardName', Arr::get($this->message->toArray(), 'name'));
    }

    /** @test */
    public function it_can_set_the_description()
    {
        $this->message->description('MyDescription');

        $this->assertEquals('MyDescription', Arr::get($this->message->toArray(), 'desc'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_string()
    {
        $date = new DateTime('tomorrow');
        $this->message->due('tomorrow');

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get($this->message->toArray(), 'due'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_datetime()
    {
        $date = new DateTime('tomorrow');
        $this->message->due($date);

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get($this->message->toArray(), 'due'));
    }

    /** @test */
    public function it_can_set_the_top_position()
    {
        $this->message->top();

        $this->assertEquals('top', Arr::get($this->message->toArray(), 'pos'));
    }

    /** @test */
    public function it_can_set_the_bottom_position()
    {
        $this->message->bottom();

        $this->assertEquals('bottom', Arr::get($this->message->toArray(), 'pos'));
    }

    /** @test */
    public function it_can_set_a_numeric_position()
    {
        $this->message->position(5);

        $this->assertEquals(5, Arr::get($this->message->toArray(), 'pos'));
    }

    public function it_can_set_a_labels()
    {
        $this->message->labels(['green', 'red']);

        $this->assertEquals('green,red', Arr::get($this->message->toArray(), 'labels'));
    }
}
