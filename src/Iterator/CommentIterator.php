<?php

namespace App\Iterator;

use IteratorAggregate;
use ArrayIterator;
use Traversable;

class CommentIterator implements IteratorAggregate
{
  private $comments;

  public function __construct(array $comments)
  {
    $this->comments = $comments;
  }

  public function getIterator(): Traversable
  {
    return new ArrayIterator($this->comments);
  }
}
