<?php

namespace NotificationChannels\Trello;

use DateTime;

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

    /** @var array|null */
    protected $comments;

    /**
     * @param string $name
     *
     * @return static
     */
    public static function create($name = '')
    {
        return new static($name);
    }

    /**
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * Set the card name.
     *
     * @param $name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the card description.
     *
     * @param $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the card position.
     *
     * @param string|int $position
     *
     * @return $this
     */
    public function position($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Set the card position to 'top'.
     *
     * @return $this
     */
    public function top()
    {
        $this->position = 'top';

        return $this;
    }

    /**
     * Set the card position to 'bottom'.
     *
     * @return $this
     */
    public function bottom()
    {
        $this->position = 'bottom';

        return $this;
    }

    /**
     * Set the card position due date.
     *
     * @param string|DateTime $due
     *
     * @return $this
     */
    public function due($due)
    {
        if (! $due instanceof DateTime) {
            $due = new DateTime($due);
        }

        $this->due = $due->format(DateTime::ATOM);

        return $this;
    }

    /**
     * Set the cards comments from array or string.
     *
     * @param array|string $comments
     *
     * @return $this
     */
    public function comments($comments)
    {
        if (! is_array($comments)) {
            $this->comments = [$comments];
        } else {
            $this->comments = $comments;
        }

        return $this;
    }

    /**
     * Add a comment to the card.
     *
     * @param array $comments
     *
     * @return $this
     */
    public function comment($comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Get comments to add to the card.
     *
     * @return array|null
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'desc' => $this->description,
            'pos' => $this->position,
            'due' => $this->due,
        ];
    }
}
