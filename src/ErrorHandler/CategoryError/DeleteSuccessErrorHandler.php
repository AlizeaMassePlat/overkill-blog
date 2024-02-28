<?php

namespace App\ErrorHandler\CategoryError;

use App\Class\Redirector;

class DeleteSuccessErrorHandler extends AbstractErrorHandler
{
  private $redirector;

  public function __construct(Redirector $redirector)
  {
    $this->redirector = $redirector;
  }

  public function handleError($request)
  {
    $this->redirector->redirect('category', ['success' => 'Category deleted successfully']);
  }
}
