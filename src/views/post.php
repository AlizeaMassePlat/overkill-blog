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
    <div id="post-<?= $post->getId() ?>">
    <h1><?= $post->getTitle() ?></h1>
    <p>Écrit par : <?= $user->getFirstName() ?> <?= $user->getLastName() ?></p> 
    <p><?= $post->getContent() ?></p>
    <p><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
    <button id="cloneButton" data-post-id="<?= $post->getId() ?>">Cloner le post</button>
</div>

    <div>
        <h2>Commentaires</h2>
        <?php foreach ($post->getComments() as $comment) : ?>
            <?php $user = new UserModel($comment->getUserId()) ?>
            <p><?= $comment->getContent() ?></p>
            <p><?= $user->getFirstname() ?> <?= $user->getLastname() ?></p>
            <p><?= $comment->getCreatedAt()->format('d/m/Y') ?></p>
        <?php endforeach; ?>
        <?php if (UserController::getUser()) : ?>
            <?php if (isset($error['error'])) : ?>
                <p><?= $error['error'] ?></p>
            <?php endif; ?>
            <h2>Ajouter un commentaire</h2>
            <form action="<?= Router::url('add_comment', ['post_id' => $post->getId()]) ?>" method="post">
                <textarea name="content" id="content" cols="30" rows="10"></textarea>
                <button type="submit">Envoyer</button>
            </form>
        <?php endif; ?>
    </div>
</form>
</body>


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
    const author = postDiv.querySelectorAll('p')[0].innerText.replace('Écrit par : ', '');
    const content = postDiv.querySelectorAll('p')[1].innerText;
    const createdAt = postDiv.querySelectorAll('p')[2].innerText;

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
