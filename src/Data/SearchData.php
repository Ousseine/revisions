<?php

namespace App\Data;

use App\Entity\Category;
use App\Entity\Tag;

class SearchData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Tag[]
     */
    public $tags = [];

    /**
     * @var Category[]
     */
    public $categories = [];

    public function __toString()
    {
        return $this->q;
    }
}