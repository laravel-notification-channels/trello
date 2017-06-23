<?php

namespace NotificationChannels\Trello;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Notifications\Notification;
use NotificationChannels\Trello\Exceptions\CouldNotAddComment;
use NotificationChannels\Trello\Exceptions\InvalidConfiguration;
use NotificationChannels\Trello\Exceptions\CouldNotSendNotification;

class TrelloChannel
{
    const API_ENDPOINT = 'https://api.trello.com/1/cards/';

    /** @var Client */
    protected $client;

    /** @var key trello key */
    protected $key;

    /** @param Client $client */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->key = config('services.trello.key');
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Trello\Exceptions\InvalidConfiguration
     * @throws \NotificationChannels\Trello\Exceptions\CouldNotSendNotification
     * @throws \NotificationChannels\Trello\Exceptions\CouldAddComment
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $routing = collect($notifiable->routeNotificationFor('Trello'))) {
            return;
        }

        if (is_null($this->key)) {
            throw InvalidConfiguration::configurationNotSet();
        }

        $trelloParameters = $notification->toTrello($notifiable)->toArray();
        $trelloCardComments = $notification->toTrello($notifiable)->getComments();

        $response = $this->client->post(self::API_ENDPOINT.'?key='.$this->key.'&token='.$routing->get('token'), [
            'form_params' => Arr::set($trelloParameters, 'idList', $routing->get('idList')),
        ]);

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        if ($trelloCardComments) {
            $this->addComments($notifiable, $trelloCardComments, $response);
        }
    }

    /**
     * Add comments to newly created card.
     * @param mixed $notifiable
     * @param array  $trelloCardComments array holding the comments to add
     * @param Response $response           response object from the trello api
     *
     * @throws \NotificationChannels\Trello\Exceptions\CouldAddComment
     */
    public function addComments($notifiable, array $trelloCardComments, $response)
    {
        if (! $routing = collect($notifiable->routeNotificationFor('Trello'))) {
            return;
        }

        $cardId = json_decode($response->getBody()->getContents())->id;
        foreach ($trelloCardComments as $comment) {
            $response = $this->client->post(self::API_ENDPOINT.$cardId.'/actions/comments?key='.$this->key.'&token='.$routing->get('token'), [
                'form_params' => ['text' => $comment],
            ]);
            if ($response->getStatusCode() !== 200) {
                throw CouldNotAddComment::serviceRespondedWithAnError($response);
            }
        }
    }
}
