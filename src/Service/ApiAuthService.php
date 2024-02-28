<?php

namespace App\Service;

use App\Interface\AuthInterface;
use App\Repository\UserRepository;

class ApiAuthService implements AuthInterface {

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function authenticate($data): bool {

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
      
        if (empty($email) || empty($password)) {
            throw new \Exception("Tous les champs sont obligatoires");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("L'email n'est pas valide");
        }

        $user = $this->userRepository->findOneByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception("Les identifiants sont incorrects");
        }

        $user->setPassword('');

        $_SESSION['user'] = [
            'name' => $user->getFirstname() . ' ' . $user->getLastname(),
            'email' => $user->getEmail(),
            'role' => $user ->getRole()[0],
            'google_loggedin' => true,
            'auth_type' => 'normal',
        ];
        return true;
    }
}