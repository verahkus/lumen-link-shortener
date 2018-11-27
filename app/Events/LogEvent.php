<?php

namespace App\Events;

class LogEvent extends Event
{
    public $title;
    public $link;
    public $request;

    /**
     * LogEvent constructor.
     *
     * @param $title
     * @param $link
     * @param $request
     */
    public function __construct($title, $link, $request)
    {
        $this->title = $title;
        $this->link = $link;
        $this->request = $request;
    }
}