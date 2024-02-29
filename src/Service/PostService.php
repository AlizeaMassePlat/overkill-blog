<?php

namespace App\Service;

use App\Repository\PostRepository;
use App\Model\PostModel;
use App\Interface\ServiceInterface;
use App\Iterator\PostIterator;

class PostService implements ServiceInterface
{
  private $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  public function create($data)
  {
    $arrayPost = $data->toArray($data);
    $title = $arrayPost['title'];
    $content = $arrayPost['content'];
    $userId = $arrayPost['user'];
    $createdAt = $arrayPost['created_at'];

    $post = new PostModel();
    $post->setTitle($title);
    $post->setContent($content);
    $post->setUserId($userId);
    $post->setCreatedAt($createdAt);

    $this->postRepository->save($post);
    
  }

  public function duplicate($id, $formData) {

    $existingPost = $this->postRepository->findOneById($id);

    if ($existingPost === null) {
        return null;
    }

    $newPost = $existingPost->clone();
    // méthodes non reconnues mais qui marchent 
    $newPost->setTitle($formData['title'] ?? 'Nouveau titre du post');
    $newPost->setContent($formData['content'] ?? 'Nouveau contenu du post');

    $this->create($newPost);

    return $newPost;
}

  public function update($post): void
  {
    $this->postRepository->save($post);
  }

  public function delete($post): void
  {
    $this->postRepository->delete($post->getId());
  }

  public function getById($id)
  {
    return $this->postRepository->findOneById($id);
  }

  public function getAll()
  {
    $posts = $this->postRepository->findAll();
    return new PostIterator($posts);
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