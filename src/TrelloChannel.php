<?php

namespace NotificationChannels\Trello;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Notifications\Notification;
use NotificationChannels\Trello\Exceptions\InvalidConfiguration;
use NotificationChannels\Trello\Exceptions\CouldNotSendNotification;

class TrelloChannel
{
    const API_ENDPOINT = 'https://api.trello.com/1/cards/';

    /** @var Client */
    protected $client;

    /** @param Client $client */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Trello\Exceptions\InvalidConfiguration
     * @throws \NotificationChannels\Trello\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $routing = collect($notifiable->routeNotificationFor('Trello'))) {
            return;
        }

        $key = config('services.trello.key');

        if (is_null($key)) {
            throw InvalidConfiguration::configurationNotSet();
        }

        $trelloParameters = $notification->toTrello($notifiable)->toArray();

        $response = $this->client->post(self::API_ENDPOINT.'?key='.$key.'&token='.$routing->get('token'), [
            'form_params' => Arr::set($trelloParameters, 'idList', $routing->get('idList')),
        ]);

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
