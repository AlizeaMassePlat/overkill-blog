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
    $title = $data['title'] ?? null;
    $content = $data['content'] ?? null;
    $userId = $data['userId'] ?? null;
    $categoryId = $data['categoryId'] ?? null;

    $post = new PostModel();
    $post->setTitle($title);
    $post->setContent($content);
    $post->setUserId($userId);
    $post->setCategoryId($categoryId);
    $post->setCreatedAt(new \DateTime());

    $this->postRepository->save($post);
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
    // Récupération des IDs des commentaires
    $commentsIds = [];
    foreach ($post->getComments() as $comment) {
      $commentsIds[] = $comment->getId();
    }

    // Construction du tableau de données
    return [
      'id' => $post->getId(),
      'title' => $post->getTitle(),
      'content' => $post->getContent(),
      'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
      'updated_at' => $post->getUpdatedAt() ? $post->getUpdatedAt()->format('Y-m-d H:i:s') : null,
      'user' => 'Placeholder for user', // $post->getUser()->getEmail(),
      'comments' => $commentsIds,
      'category' => 'Placeholder for category' // $post->getCategory()->getName()
    ];
  }


}