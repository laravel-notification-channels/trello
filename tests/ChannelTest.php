<?php

namespace NotificationChannels\Trello\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Trello\Exceptions\CouldNotSendNotification;
use NotificationChannels\Trello\Exceptions\InvalidConfiguration;
use NotificationChannels\Trello\TrelloChannel;
use NotificationChannels\Trello\TrelloMessage;
use Orchestra\Testbench\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $this->app['config']->set('services.trello.key', 'TrelloKey');

        $response = new Response(200);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with('https://api.trello.com/1/cards/?key=TrelloKey&token=NotifiableToken',
                [
                    'form_params' => [
                        'name' => 'TrelloName',
                        'desc' => 'TrelloDescription',
                        'pos' => 'top',
                        'due' => null,
                        'idList' => 'TrelloListId'
                    ]
                ])
            ->andReturn($response);
        $channel = new TrelloChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_is_not_configured()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $client = new Client();
        $channel = new TrelloChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->app['config']->set('services.trello.key', 'TrelloKey');

        $response = new Response(500);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->andReturn($response);
        $channel = new TrelloChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForTrello()
    {
        return [
            'token' => 'NotifiableToken',
            'idList' => 'TrelloListId',
        ];
    }
}


class TestNotification extends Notification
{
    public function toTrello($notifiable)
    {
        return (
            (new TrelloMessage('TrelloName'))
                ->description('TrelloDescription')
                ->top()
        );
    }
}