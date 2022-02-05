# Trello notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/trello.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/trello)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/trello/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/trello)
[![StyleCI](https://styleci.io/repos/65379321/shield)](https://styleci.io/repos/65379321)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/9015691f-130d-4fca-8710-72a010abc684.svg?style=flat-square)](https://insight.sensiolabs.com/projects/9015691f-130d-4fca-8710-72a010abc684)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/trello.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/trello)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/trello/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/trello/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/trello.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/trello)

This package makes it easy to create [Trello cards](https://developers.trello.com/) with Laravel 6.x, 7.x, 8.x & 9.x.

## Contents

- [Installation](#installation)
    - [Setting up the Trello service](#setting-up-the-trello-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/trello
```

### Setting up the Trello service

Add your Trello REST API Key to your `config/services.php`:

```php
// config/services.php
...
'trello' => [
    'key' => env('TRELLO_API_KEY'),
],
...
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Trello\TrelloChannel;
use NotificationChannels\Trello\TrelloMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    public function via($notifiable)
    {
        return [TrelloChannel::class];
    }

    public function toTrello($notifiable)
    {
        return TrelloMessage::create()
            ->name("Trello Card Name")
            ->description("This is the Trello card description")
            ->top()
            ->due('tomorrow');
    }
}
```

In order to let your Notification know which Trello user and Trello list you are targeting, add the `routeNotificationForTrello` method to your Notifiable model.

This method needs to return an array containing the access token of the authorized Trello user (if it's a private board) and the list ID of the Trello list to add the card to.

```php
public function routeNotificationForTrello()
{
    return [
        'token' => 'NotifiableToken',
        'idList' => 'TrelloListId',
    ];
}
```

### Available methods

- `name('')`: Accepts a string value for the Trello card name.
- `description('')`: Accepts a string value for the Trello card description.
- `top()`: Moves the Trello card to the top.
- `bottom()`: Moves the Trello card to the bottom.
- `position('')`: Accepts an integer for a specific Trello card position.
- `due('')`: Accepts a string or DateTime object for the Trello card due date.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email m.pociot@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
