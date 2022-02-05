<?php

namespace NotificationChannels\Trello\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet(): static
    {
        return new static('In order to send notification via Trello you need to add credentials in the `trello` key of `config.services`.');
    }
}
