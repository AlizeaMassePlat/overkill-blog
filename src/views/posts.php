<?php

use App\Router\Router;
use App\Class\User;
use App\Model\UserModel;

?>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px;">

    <h1 style="text-align: center;">Tous les articles</h1>
    <?php foreach ($posts as $post) : ?>
        <article style="margin-bottom: 20px; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h2><?= $post->getTitle() ?></h2>
            <p><?= $post->getContent() ?></p>
            <p><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
            <?php $user = new UserModel($post->getUserId()) ?>
            <p><?= $user->getFirstname() ?> <?= $user->getLastname() ?></p>
            <a href="<?= Router::url('post', ['id' => $post->getId()]) ?>" style="display: block; margin-top: 10px; color: #007bff; text-decoration: none;">Voir l'article</a>
        </article>
    <?php endforeach ?>
    <?php for ($i = 1; $i <= $pages; $i++) : ?>
        <a href="<?= Router::url('posts', ['page' => $i]) ?>" style="display: inline-block; margin-right: 10px; color: #007bff; text-decoration: none;"><?= $i ?></a>
    <?php endfor ?>

</body>