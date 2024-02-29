<?php
namespace App\Controller;

use App\Class\Redirector;
use App\View\ViewRenderer;
use App\Repository\PostRepository;
use App\Interface\ControllerInterface;
use App\Interface\ServiceInterface;
use App\Iterator\PostIterator;

class PostController implements ControllerInterface
{
  private $postService;
  private $viewRenderer;
  private $redirector;
  private $postRepository; 

  public function __construct(ServiceInterface $postService, ViewRenderer $viewRenderer, Redirector $redirector, PostRepository $postRepository)
  {
    $this->postService = $postService;
    $this->viewRenderer = $viewRenderer;
    $this->redirector = $redirector;
    $this->postRepository = $postRepository; 
  }

  public function create($request)
  {
    $title = $request['title'];
    $content = $request['content'];
    $userId = $request['userId'];
    $createdAt = $request['createdAt'];

  
    if ( is_null($userId) || empty($content) || empty($title)) {
      $this->redirector->redirect('posts', ['1', 'error' => 'Article invalide']);
      return;
    }

    try {
      $this->postService->create($request);
      $this->redirector->redirect('posts', ['1']);
    } catch (\Exception $e) {
      $this->redirector->redirect('posts', ['1', 'error' => $e->getMessage()]);
    }
  }

  public function update($request)
  {
    $postId = $request['id'] ?? null;
    $content = $request['content'] ?? '';
    $title = $request['title'] ?? '';
    $userId = $_SESSION['user']->getId();

    if (is_null($postId) || is_null($userId) || empty($content) || empty($title)) {
      $this->redirector->redirect('posts', ['1', 'error' => 'Données de publication invalides']);
      return;
    }
    try {
    } catch (\Exception $e) {
      $this->redirector->redirect('posts', ['1', 'error' => $e->getMessage()]);
    }
  }

  public function delete($request)
  {
    $postId = $request['id'] ?? null;
    if (is_null($postId)) {
      $this->redirector->redirect('posts', ['1', 'error' => 'ID de publication invalide']);
      return;
    }
    try {
    } catch (\Exception $e) {
      $this->redirector->redirect('posts', ['1', 'error' => $e->getMessage()]);
    }
  }


  public function paginatedPosts(int $page)
  {
    $pageNumber = (int) $page;
    $postIterator = new PostIterator($this->postRepository->findAllPaginated($pageNumber));
    $pages = count($this->postRepository->findAll()) / 10;
    $this->viewRenderer->render('posts', ['posts' => $postIterator, 'pages' => $pages]);
  }

  public function viewPost($id, $error = null)
  {
    if (is_numeric($id) === false) {
      throw new \Exception("L'identifiant du post n'est pas valide");
    }
    $post = $this->postRepository->findOneById((int) $id);
    $this->viewRenderer->render('post', ['post' => $post, 'error' => $error]);
  }
}
