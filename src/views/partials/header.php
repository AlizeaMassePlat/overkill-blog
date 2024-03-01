<?php

use App\Controller\UserController;
use App\Router\Router; // Ajout de l'utilisation de la classe Router
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

// Fonction pour obtenir le nom du fichier actuel
function getCurrentPage()
{
    return basename($_SERVER['SCRIPT_NAME']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stupid Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        nav ul li a:hover,
        nav ul li a:focus,
        nav ul li.active a {
            font-weight: bold;
            color: yellow;
        }
    </style>
</head>

<body>
    <header>
        <h1>Stupid Blog</h1>
        <nav>
            <ul>
                <li <?php if (getCurrentPage() === 'home.php') echo 'class="active"'; ?>><a href="<?= Router::url('home') ?>">Accueil</a></li>
                <li <?php if (getCurrentPage() === 'posts.php') echo 'class="active"'; ?>><a href="<?= Router::url('posts', ['page' => 1]) ?>">Articles</a></li>
                <?php if ($estConnecte) : ?>
                    <li <?php if (getCurrentPage() === 'profile.php') echo 'class="active"'; ?>><a href="<?= Router::url('profile') ?>">Profil</a></li>
                    <li><a href="<?= Router::url('logout') ?>">Se déconnecter</a></li>
                <?php else : ?>
                    <li <?php if (getCurrentPage() === 'login.php') echo 'class="active"'; ?>><a href="<?= Router::url('login') ?>">Se connecter</a></li>
                    <li <?php if (getCurrentPage() === 'register.php') echo 'class="active"'; ?>><a href="<?= Router::url('register') ?>">S'inscrire</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </header>
</body>

</html>