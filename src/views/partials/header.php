<?php

use App\Class\Controller;
use App\Controller\UserController;
use App\Router\Router;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Class\Database;
use App\Factory\UserFactory;

$db = new Database();
$connection = $db->getConnection();

$userService = new UserService(new UserRepository($connection));

$estConnecte = isset($_SESSION['api_loggedin']) || isset($_SESSION['user']['google_loggedin']);
if (isset($_SESSION['user']['auth_type'])) {
    if ($_SESSION['user']['auth_type'] == "google") {
        echo '<p>Vous êtes connecté grâce à Google.</p>';
    } else {
        echo '<p>Vous êtes connecté normalement.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stupid Blog</title>
</head>

<header>
    <h1>Stupid Blog</h1>
    
    <?php if ($_SESSION) : ?>
    <?php  $userType = $_SESSION['user']['role'];
    
$user = UserFactory::createUser($userType); 
// var_dump($userType); die;
?>
       
        <?php $userId = UserController::getUser()->getId(); ?>
        <p> <?= $user->getWelcomeMessage() ?></p>
    <?php endif ?>
    <nav>
        <ul>
            <li><a href="<?= Router::url('home') ?>">Accueil</a></li>
            <li><a href="<?= Router::url('posts', ['page' => 1]) ?>">Articles</a></li>
                <?php if ($estConnecte) : ?>
                <li><a href="<?= Router::url('profile') ?>">Profil</a></li>
                <li><a href="<?= Router::url('logout') ?>">Se déconnecter</a></li>
            <?php else : ?>
                <li><a href="<?= Router::url('login') ?>">Se connecter</a></li>
                <li><a href="<?= Router::url('register') ?>">S'inscrire</a></li>
            <?php endif ?>
        </ul>
    </nav>
</header>