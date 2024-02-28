<?php

namespace App\Iterator; 

use IteratorAggregate;
use ArrayIterator;
use Traversable;

class PostIterator implements IteratorAggregate
{
    private $posts;

    public function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->posts);
    }
}
