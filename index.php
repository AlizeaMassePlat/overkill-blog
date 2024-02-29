<?php

use App\Router\Router;
use App\Class\Database;
use App\Class\Redirector;
use App\View\ViewRenderer;
use App\Router\RouterFacade;
use App\Service\PostService;
use App\Service\UserService;
use App\Service\ApiAuthService;
use App\Service\CommentService;
use App\Controller\PostController;
use App\Controller\UserController;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\GoogleAuthService;
use App\Decorator\BaseAuthDecorator;
use App\Controller\CommentController;
use App\Repository\CommentRepository;

require_once 'vendor/autoload.php';

session_start();

// Initialisation des services
$services = [];
$services['db'] = function () {
    return new Database();
};
$services['viewRenderer'] = function () {
    return new ViewRenderer();
};
$services['redirector'] = function () {
    return new Redirector();
};
$services['userRepository'] = function () use ($services) {
    return new UserRepository($services['db']()->getConnection());
};

$services['postRepository'] = function () use ($services) {
    return new PostRepository($services['db']()->getConnection());
};

$services['commentRepository'] = function () use ($services) {
    return new CommentRepository($services['db']()->getConnection());
};

$services['userService'] = function () use ($services) {
    return new UserService($services['userRepository']());
};

$services['postService'] = function () use ($services) {
    return new PostService($services['postRepository']());
};

$services['commentService'] = function () use ($services) {
    return new CommentService($services['commentRepository']());
};

$services['apiAuthService'] = function () use ($services) {
    return new ApiAuthService($services['userRepository']());
};

$services['googleAuthService'] = function () {
    return new GoogleAuthService();
};

$services['baseAuthDecorator'] = function () use ($services) {
    return new BaseAuthDecorator($services['apiAuthService'](), $services['googleAuthService']());
};

// Création des instances de contrôleurs
$userController = new UserController($services['userService'](), $services['viewRenderer'](), $services['redirector'](), $services['apiAuthService'](), $services['baseAuthDecorator']());
$postController = new PostController($services['postService'](), $services['viewRenderer'](), $services['redirector'](), $services['postRepository']());
$commentController = new CommentController($services['commentService'](), $services['viewRenderer'](), $services['redirector']());

// Configuration des routes avec la Facade
RouterFacade::setBasePath('/stupid-blog-overkill/');

RouterFacade::get('/', function () use ($services) {
    $services['viewRenderer']()->render('index');
}, "home");

// Authentification
RouterFacade::get('/register', function () use ($services) {
    try {
        $services['viewRenderer']()->render('register');
    } catch (\Exception $e) {
        $services['viewRenderer']()->render('register', ['error' => $e->getMessage()]);
    }
}, "register");

RouterFacade::post('/register', function () use ($userController) {
    $userController->registerUser($_POST);
}, "register");

RouterFacade::get('/login', function () use ($services) {
    $services['viewRenderer']()->render('login');
}, "login");

RouterFacade::post('/login', function () use ($userController) {
    $userController->loginUser($_POST);
}, "login");

RouterFacade::get('/google', function () use ($userController) {
    $userController->loginUser(['google' => true]);
}, "google");

RouterFacade::get('/auth/google/callback', function () use ($services) {
    $googleAuthService = $services['googleAuthService']();
    $googleAuthService->handleGoogleCallback($_GET);
}, "google_callback");


RouterFacade::get('/logout', function () use ($userController) {
    $userController->logoutUser();
}, "logout");

// Profile
RouterFacade::get('/profile', function () use ($userController) {
    $userController->profile();
}, "profile");

RouterFacade::post('/profile', function () use ($userController) {
    $userController->update($_POST);
}, "profile");

// Pagination 

RouterFacade::get('/posts/:page', function ($page = 1) use ($postController) {
    $pageNumber = is_numeric($page) ? (int)$page : 1; 
    $postController->paginatedPosts($pageNumber);
}, "posts")->with('page', '[0-9]+');

// Affichage et création des postes
RouterFacade::get('/post/:id', function ($id) use ($postController) {
    $postController->viewPost($id);
}, "post")->with('id', '[0-9]+');


RouterFacade::post('/clone_post/:post_id', function ($postId) use ($services) {
       $formData = $_POST; 
       $formData['userId'] = $_SESSION['user']['id']; 

    $newPost = $services['postService']()->duplicate($postId, $formData);
    if ($newPost) {
        $services['redirector']()->redirect('posts', ['page' => 1]);
    } else {
    }
}, 'clone_post')->with('post_id', '[0-9]+');



// Créer un commentaire 

RouterFacade::post('/comments/:post_id', function ($post_id) use ($commentController, $services) {
    try {
        $_POST['post_id'] = $post_id;
        $commentController->create($_POST);
    } catch (\Exception $e) {
        $services['redirector']()->redirect('post', ['id' => $post_id, 'error' => $e->getMessage()]);
    }
}, "add_comment")->with('post_id', '[0-9]+');

// Gestion administrateur
RouterFacade::get('/admin/:action/:entity', function ($action = 'list', $entity = 'user') use ($services) {
    $services['userService']()->admin($action, $entity);
}, "admin")->with('action', 'list')->with('entity', 'user|post|comment|category');

RouterFacade::get('/admin/:action/:entity/:id', function ($action, $entity, $id = null) use ($services) {
    $services['userService']()->admin($action, $entity, $id);
}, "admin-entity")->with('action', 'show|edit|delete')->with('entity', 'user|post|comment|category')->with('id', '[0-9]+');

RouterFacade::post('/admin/:action/:entity/:id', function ($action, $entity, $id = null) use ($services) {
    $services['userService']()->admin($action, $entity, $id);
}, "admin-entity-action")->with('action', 'edit|delete')->with('entity', 'user|post|comment|category')->with('id', '[0-9]+');

// Exécution du routage
RouterFacade::run();
