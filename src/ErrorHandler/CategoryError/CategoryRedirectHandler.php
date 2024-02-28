<?php

namespace App\Controller;

use App\Class\Redirector;
use App\ErrorHandler\CategoryError\CategoryNotFoundErrorHandler;

class CategoryRedirectHandler
{
  private $redirector;

  public function __construct(Redirector $redirector)
  {
    $this->redirector = $redirector;
  }

  public function handleErrorRedirect($errorMessage)
  {
    $this->redirector->redirect('category', ['error' => $errorMessage]);
  }

  public function handleCategoryNotFound($categoryService, $request)
  {
    $errorHandler = new CategoryNotFoundErrorHandler($categoryService, $this->redirector);
    $errorHandler->handleError($request);
  }

  public function handleSuccessRedirect($successMessage)
  {
    $this->redirector->redirect('category', ['success' => $successMessage]);
  }
}
