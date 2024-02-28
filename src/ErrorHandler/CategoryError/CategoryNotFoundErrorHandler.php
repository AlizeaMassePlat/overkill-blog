<?php

namespace App\ErrorHandler\CategoryError;

use App\Service\CategoryService;
use App\Class\Redirector;

class CategoryNotFoundErrorHandler extends AbstractErrorHandler
{
  private $categoryService;
  private $redirector;

  public function __construct(CategoryService $categoryService, Redirector $redirector)
  {
    $this->categoryService = $categoryService;
    $this->redirector = $redirector;
  }

  public function handleError($request)
  {
    $categoryId = $request['id'] ?? null;
    if (!$this->categoryService->getById($categoryId)) {
      $this->redirector->redirect('category', ['error' => 'Category not found']);
      return 'Category not found';
    } elseif ($this->nextHandler) {
      return $this->nextHandler->handleError($request);
    } else {
      return null;
    }
  }
}
