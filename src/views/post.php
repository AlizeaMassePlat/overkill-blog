<?php

use App\Router\Router;
use App\Class\Database;
use App\Model\PostModel;
use App\Model\UserModel;
use App\Service\PostService;
use App\Service\UserService;
use App\Service\CategoryService;
use App\Controller\UserController;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;

/** @var PostModel $post */
$post;

$db = new Database();
$connection = $db->getConnection();

// $categoryService = new CategoryService(new CategoryRepository($connection));
$userService = new UserService(new UserRepository($connection));
$postService = new PostService(new PostRepository($connection));

// $category = $categoryService->getById($post->getCategoryId());
$user = $userService->getById($post->getUserId());

?>

<body>
    <style>
        /* Styles CSS intégrés ici */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .post-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .post-content {
            margin-bottom: 20px;
        }

        .post-content p {
            margin: 10px 0;
        }

        #cloneButton {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            cursor: pointer;
        }

        #cloneButton:hover {
            background-color: #0056b3;
        }

        .comment-container {
            margin-top: 20px;
        }

        .comment {
            margin-bottom: 20px;
        }
    </style>

    <div class="post-container" id="post-<?= $post->getId() ?>">
        <h1><?= $post->getTitle() ?></h1>
        <p class="post-content">Écrit par : <?= $user->getFirstName() ?> <?= $user->getLastName() ?></p>
        <p class="post-content"><?= $post->getContent() ?></p>
        <p class="post-content"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
        <button id="cloneButton" data-post-id="<?= $post->getId() ?>">Cloner le post</button>
    </div>

    <div class="comment-container">
        <h2>Commentaires</h2>
        <?php foreach ($post->getComments() as $comment) : ?>
            <?php $user = new UserModel($comment->getUserId()) ?>
            <div class="comment">
                <p><?= $comment->getContent() ?></p>
                <p><?= $user->getFirstname() ?> <?= $user->getLastname() ?></p>
                <p><?= $comment->getCreatedAt()->format('d/m/Y') ?></p>
            </div>
        <?php endforeach; ?>
        <?php if (UserController::getUser()) : ?>
            <div style="margin-top: 20px; margin-bottom: 20px;   padding: 20px; background-color: #f9f9f9; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 600px; margin-left: auto; margin-right: auto;">
                <?php if (isset($error['error'])) : ?>
                    <p style="color: red;"><?= $error['error'] ?></p>
                <?php endif; ?>
                <h2 style="margin-bottom: 10px;">Ajouter un commentaire</h2>
                <form action="<?= Router::url('add_comment', ['post_id' => $post->getId()]) ?>" method="post">
                    <textarea name="content" id="content" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" cols="30" rows="5" placeholder="Votre commentaire"></textarea>
                    <button type="submit" style="margin-top: 10px; padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Envoyer</button>
                </form>
            </div>
        <?php endif; ?>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cloneButton').addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                transformPostToForm(postId);
            });
        });

        function transformPostToForm(postId) {
            const postDiv = document.getElementById(`post-${postId}`);
            const title = postDiv.querySelector('h1').innerText;
            const author = postDiv.querySelector('.post-content').innerText.replace('Écrit par : ', '');
            const content = postDiv.querySelectorAll('.post-content')[1].innerText;
            const createdAt = postDiv.querySelectorAll('.post-content')[2].innerText;

            postDiv.innerHTML = `
                <form action="/stupid-blog-overkill/clone_post/${postId}" method="POST">
                    <input type="hidden" name="postId" value="${postId}">
                    <input type="text" name="title" value="${title}" required> 
                    <p>Écrit par : <input type="text" name="author" value="${author}" required></p>
                    <textarea name="content" required>${content}</textarea>
                    <p>Date de création : <input type="text" name="createdAt" value="${createdAt}" required></p>
                    <button>Enregistrer le nouveau post</button>
                </form>
            `;
        }
    </script>
</body>