<?php

namespace App\ErrorHandler\CategoryError;

use App\ErrorHandler\CategoryError\AbstractErrorHandler;

class EmptyCategoryIdOrNameErrorHandler extends AbstractErrorHandler
{
  public function handleError($request): ?string
  {
    $categoryId = $request['id'] ?? null;
    $name = $request['name'] ?? '';

    if (is_null($categoryId) || empty($name)) {
      return 'Invalid category data';
    }
    // Si le gestionnaire suivant est défini, passez la requête à ce gestionnaire
    if ($this->nextHandler !== null) {
      return $this->nextHandler->handleError($request);
    }

    // Si aucun gestionnaire suivant n'est défini, retournez null
    return null;
  }
}
