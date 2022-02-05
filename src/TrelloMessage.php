<?php

namespace NotificationChannels\Trello;

use DateTime;
use DateTimeInterface;

class TrelloMessage
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var string|int */
    protected $position;

    /** @var string|null */
    protected $due;

    /**
     * @param  string  $name
     * @return static
     */
    public static function create(string $name = ''): TrelloMessage
    {
        return new static($name);
    }

    /**
     * @param  string  $name
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * Set the card name.
     *
     * @param  string  $name
     * @return $this
     */
    public function name(string $name): TrelloMessage
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the card description.
     *
     * @param  string  $description
     * @return $this
     */
    public function description(string $description): TrelloMessage
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the card position.
     *
     * @param  string|int  $position
     * @return $this
     */
    public function position(string $position): TrelloMessage
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Set the card position to 'top'.
     *
     * @return $this
     */
    public function top(): TrelloMessage
    {
        $this->position = 'top';

        return $this;
    }

    /**
     * Set the card position to 'bottom'.
     *
     * @return $this
     */
    public function bottom(): TrelloMessage
    {
        $this->position = 'bottom';

        return $this;
    }

    /**
     * Set the card position due date.
     *
     * @param  string|DateTime  $due
     * @return $this
     *
     * @throws \Exception
     */
    public function due($due): TrelloMessage
    {
        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }

        $this->due = $due->format(DateTimeInterface::ATOM);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'desc' => $this->description,
            'pos' => $this->position,
            'due' => $this->due,
        ];
    }
}
