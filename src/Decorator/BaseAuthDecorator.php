<?php

namespace App\Decorator;

use App\Interface\AuthInterface;

class BaseAuthDecorator implements AuthInterface {
    protected $authService;
    protected $googleAuthService;

    public function __construct(AuthInterface $authService, AuthInterface $googleAuthService) {
        $this->authService = $authService;
        $this->googleAuthService = $googleAuthService;

    }

    public function authenticate($data): bool {
        // return $this->authService->authenticate($data);
        if (isset($data['google'])) {
            return $this->googleAuthService->authenticate($data);
        } else {
            return $this->authService->authenticate($data);
        }
    }

        public function getAuthService(): AuthInterface {
            return $this->authService;
        }
    
        public function getGoogleAuthService(): AuthInterface {
            return $this->googleAuthService;
        }
    
}