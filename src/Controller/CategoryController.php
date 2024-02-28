<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Class\Redirector;
use App\ErrorHandler\CategoryError\ErrorHandlerInterface;
use App\ErrorHandler\CategoryError\CategoryNotFoundErrorHandler;
use App\ErrorHandler\CategoryError\DeleteSuccessErrorHandler;
use App\ErrorHandler\CategoryError\UpdateSuccessErrorHandler;

class CategoryController
{
  private $categoryService;
  private $redirector;
  private $errorHandler;

  public function __construct(CategoryService $categoryService, Redirector $redirector, ErrorHandlerInterface $errorHandler)
  {
    $this->categoryService = $categoryService;
    $this->redirector = $redirector;
    $this->errorHandler = $errorHandler;
  }
  
  public function create($request)
  {
    // Gestion des erreurs lors de la création d'une catégorie
    $error = $this->errorHandler->handleError($request);
    if ($error) {
      // Redirection en cas d'erreur
      $this->handleErrorRedirect($error);
      return;
    }

    // Création de la catégorie
    $name = $request['name'];
    try {
      $this->categoryService->create($name);
      // Redirection en cas de succès
      $this->handleSuccessRedirect('Category created successfully');
    } catch (\Exception $e) {
      // Gestion des erreurs lors de l'exception
      $this->handleErrorRedirect($e->getMessage());
    }
  }

  public function update($request)
  {
    // Gestion des erreurs lors de la mise à jour d'une catégorie
    $error = $this->errorHandler->handleError($request);
    if ($error) {
      // Redirection en cas d'erreur
      $this->redirector->redirect('category', ['error' => $error]);
      return;
    }

    // Récupération des données de la requête
    $categoryId = $request['id'] ?? null;
    $name = $request['name'] ?? '';

    try {
      // Récupération de la catégorie à mettre à jour
      $category = $this->categoryService->getById($categoryId);
      if (!$category) {
        // Gestion de l'erreur si la catégorie n'est pas trouvée
        $this->errorHandler = new CategoryNotFoundErrorHandler($this->categoryService, $this->redirector);
        $this->errorHandler->handleError($request);
        return;
      }

      // Mise à jour de la catégorie
      $category->setName($name);
      $this->categoryService->update($category);

      // Redirection en cas de succès
      $this->errorHandler = new UpdateSuccessErrorHandler($this->redirector);
      $this->errorHandler->handleError($request);
    } catch (\Exception $e) {
      // Gestion des erreurs lors de l'exception
      $this->redirector->redirect('category', ['error' => $e->getMessage()]);
    }
  }

  public function delete($request)
  {
    // Gestion des erreurs lors de la suppression d'une catégorie
    $error = $this->errorHandler->handleError($request);
    if ($error) {
      // Redirection en cas d'erreur
      $this->handleErrorRedirect($error);
      return;
    }
    // Récupération de l'identifiant de la catégorie à supprimer
    $categoryId = $request['id'] ?? null;

    try {
      // Récupération de la catégorie à supprimer
      $category = $this->categoryService->getById($categoryId);
      if (!$category) {
        // Gestion de l'erreur si la catégorie n'est pas trouvée
        $errorHandler = new CategoryNotFoundErrorHandler($this->categoryService, $this->redirector);
        $errorHandler->handleError($request);
        return;
      }

      // Suppression de la catégorie
      $this->categoryService->delete($category);

      // Redirection en cas de succès
      $errorHandler = new DeleteSuccessErrorHandler($this->redirector);
      $errorHandler->handleError($request);
    } catch (\Exception $e) {
      // Gestion des erreurs lors de l'exception
      $this->handleErrorRedirect($e->getMessage());
    }
  }

  // Fonction privée pour gérer la redirection en cas de succès
  private function handleSuccessRedirect($successMessage)
  {
    $this->redirector->redirect('category', ['success' => $successMessage]);
  }

  // Méthode privée pour gérer les redirections en cas d'erreur
  private function handleErrorRedirect($errorMessage)
  {
    $this->redirector->redirect('category', ['error' => $errorMessage]);
  }
}
