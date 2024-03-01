<?php

use App\Controller\UserController;
use App\Router\Router;

?>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px;">

    <h1 style="text-align: center;">Bonjour, <?= UserController::getUser()->getFirstname() ?> <?= UserController::getUser()->getLastname() ?> bienvenu.e sur ton profil</h1>
    <p style="text-align: center;">Voici tes informations personnelles :</p>
    <div style="max-width: 400px; margin: 0 auto; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <p>Email : <?= UserController::getUser()->getEmail() ?></p>
        <p>Prénom : <?= UserController::getUser()->getFirstname() ?></p>
        <p>Nom : <?= UserController::getUser()->getLastname() ?></p>
    </div>
    <form action="<?= Router::url('profile') ?>" method="post" style="max-width: 400px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div style="margin-bottom: 20px;">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= UserController::getUser()->getEmail() ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" value="<?= UserController::getUser()->getFirstname() ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" value="<?= UserController::getUser()->getLastname() ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <input type="submit" value="Modifier" style="width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer;">
        </div>
    </form>
    <div style="text-align: center;">
        <a href="<?= Router::url('logout') ?>" style="color: #007bff; text-decoration: none;">Se déconnecter</a>
    </div>

</body>