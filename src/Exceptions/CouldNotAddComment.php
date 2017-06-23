<?php

namespace NotificationChannels\Trello\Exceptions;

class CouldNotAddComment extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static('Error adding comment. Trello responded with an error: `' . $response->getBody()->getContents() . '`');
    }
}
