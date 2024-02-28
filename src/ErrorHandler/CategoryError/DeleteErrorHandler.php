<?php

namespace App\ErrorHandler\CategoryError;

class DeleteErrorHandler extends AbstractErrorHandler
{
  public function handleError($request): ?string
  {
    $categoryId = $request['id'] ?? null;
    if (is_null($categoryId)) {
      return 'Invalid category ID';
    }

    // Si le gestionnaire suivant est défini, passez la requête à ce gestionnaire
    if ($this->nextHandler !== null) {
      return $this->nextHandler->handleError($request);
    }

    // Si aucun gestionnaire suivant n'est défini, retournez null
    return null;
  }
}
