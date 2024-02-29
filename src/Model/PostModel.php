<?php 

namespace App\Model;

use App\Interface\PostPrototypeInterface;
use App\Model\CommentModel;
use App\Service\PostService;

class PostModel implements PostPrototypeInterface
{
  private $id;
  private $title;
  private $content;
  private $createdAt;
  private $updatedAt;
  private $userId;
  private $comments;

  public function __construct($id = null, PostService $service = null)
  {
      if ($id !== null && $service !== null) {
          $post = $service->getById($id);
          if ($post) {
              $this->id = $post->id;
              $this->title = $post->title;
              $this->content = $post->content;
              $this->createdAt = $post->createdAt;
              $this->updatedAt = $post->updatedAt;
              $this->userId = $post->userId;
              $this->comments = $post->comments ?? [];
          }
      } else {
          $this->createdAt = new \DateTime(); 
      }
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function setUserId($userId)
  {
    $this->userId = $userId;
  }

  public function getComments()
  {
    return $this->comments;
  }

  public function setComments($comments)
  {
    $this->comments = $comments;
    foreach ($comments as $comment) {
      $comment->setPostId($this->getId());
    }

    return $this;
  }

  public function __toString() {
    return $this->title; 
}

  public function addComment(CommentModel $comment)
  {
    if (!in_array($comment, $this->comments) && $comment->getPostId() === $this->id) {
      $this->comments[] = $comment;
    }
    $this->comments[] = $comment;

    return $this;
  }

  public function removeComment(CommentModel $comment)
  {
    $key = array_search($comment, $this->comments);
    if ($key !== false) {
      unset($this->comments[$key]);
    }

    return $this;
  }


  public function clone(): PostPrototypeInterface {
    $clone = clone $this; 
    return $clone;
}

public function toArray($post): array
  {
    $commentsIds = [];
    foreach ($post->getComments() as $comment) {
      $commentsIds[] = $comment->getId();
    }

    return [
      'id' => $post->getId(),
      'title' => $post->getTitle(),
      'content' => $post->getContent(),
      'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
      'updated_at' => $post->getUpdatedAt() ? $post->getUpdatedAt()->format('Y-m-d H:i:s') : null,
      'user' => $post->getUserId(),
      'comments' => $commentsIds,
    ];
  }

}