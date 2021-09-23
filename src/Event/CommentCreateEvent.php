<?php

namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;

use App\Entity\Comment;

class CommentCreateEvent extends Event
{
    protected $comment;

    public function __construct(Comment $comment)
    {   
        $this->comment = $comment;   
    }

    public function getComment()
    {
        return $this->comment;
    }
}