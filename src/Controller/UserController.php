<?php

namespace App\Controller;

use App\Service\UserService;
use App\View\ViewRenderer;
use App\Class\Redirector;
use App\Decorator\BaseAuthDecorator;
use App\Interface\ControllerInterface;
use App\Model\UserModel;
use App\Service\ApiAuthService;

class UserController implements ControllerInterface
{
    protected $authService;
    protected $userService;
    protected $viewRenderer;
    protected $redirector;
    protected $baseAuthDecorator;

    public function __construct(UserService $userService, ViewRenderer $viewRenderer, Redirector $redirector, ApiAuthService $authService, BaseAuthDecorator $baseAuthDecorator)
    {
        $this->userService = $userService;
        $this->viewRenderer = $viewRenderer;
        $this->redirector = $redirector;
        $this->authService = $authService;
        $this->baseAuthDecorator = $baseAuthDecorator;
    }

    public function create($request) {
        try {
            $email = $request['email'] ?? '';
            $password = $request['password'] ?? '';
            $confirmPassword = $request['confirm_password'] ?? '';
            $firstname = $request['firstname'] ?? '';
            $lastname = $request['lastname'] ?? '';

            $this->userService->create($email, $password, $confirmPassword, $firstname, $lastname);

            $this->redirector->redirect('login');
        } catch (\Exception $e) {
            $this->redirector->redirect('register', ['error' => $e->getMessage()]);
        }
    }


    public function update($request) {
        try {
            $this->userService->update($request);

            $this->redirector->redirect('profile', ['success' => 'Profil mis à jour avec succès']);
        } catch (\Exception $e) {
            $this->redirector->redirect('profile', ['error' => $e->getMessage()]);
        }
    }

    public function delete($request) {
        try {
            $userId = $request['user_id'] ?? null;
            $this->userService->delete($userId);
            $this->redirector->redirect('home', ['success' => 'Utilisateur supprimé avec succès']);
        } catch (\Exception $e) {
            $this->redirector->redirect('profile', ['error' => $e->getMessage()]);
        }
    }


    // Authentification 

    public function registerUser($data)
    {
        try {
            $this->userService->register($data);
            $this->redirector->redirect('login');
        } catch (\Exception $e) {
            $this->redirector->redirect('register', ['error' => $e->getMessage()]);
        }
    }

    public function loginUser($data) {
        try {
            
            $this->baseAuthDecorator->authenticate($data);
            $this->redirector->redirect('home');
        } catch (\Exception $e) {
            $this->redirector->redirect('login', ['error' => $e->getMessage()]);
        }
    }


    public function logoutUser()
    {
        session_start();
        unset($_SESSION['user'], $_SESSION['google_loggedin'], $_SESSION['auth_type']);
        session_destroy(); 
        $this->redirector->redirect('home');
    }

    
    public static function getUser() {
        // var_dump($_SESSION);die; 
        if (isset($_SESSION['user'])) {
            // Si vous avez stocké un objet UserModel pour les utilisateurs de l'API
            if ($_SESSION['user'] instanceof UserModel) {
                return $_SESSION['user'];
            }
            
            elseif (is_array($_SESSION['user'])) {
                $user = new UserModel();
                $user->setEmail($_SESSION['user']['email'] ?? null);
                    $fullName = $_SESSION['user']['name'] ?? '';
                    $parts = explode(' ', $fullName, 2);
                    $firstname = $parts[0] ?? null;
                    $lastname = $parts[1] ?? null; 
                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                return $user;
            }
        }
        return null;
    }
    
    

    public function profile() {
        $user = self::getUser();

        if ($user === null) { 
            $this->redirector->redirect('login');
            return;
        }
    
        // Aucun besoin de récupérer l'utilisateur de la base de données si vous avez toutes les informations nécessaires dans la session
        // Si vous avez besoin de plus d'informations de la base de données, assurez-vous que $user->getId() existe et est valide
        // $user = $this->userService->getById($userId); // Seulement si nécessaire
        
        $this->viewRenderer->render('profile', ['user' => $user]);
    }

}
