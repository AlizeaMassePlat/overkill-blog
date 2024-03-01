<?php

use App\Router\Router;

?>

<body style="padding: 20px">
    <h1 style="text-align: center;">Register</h1>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif ?>
    <form action="<?= Router::url('register') ?>" method="post" style="max-width: 400px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password_confirm">Password Confirm</label>
            <input type="password" name="password_confirm" id="password_confirm" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="firstname">Firstname</label>
            <input type="text" name="firstname" id="firstname" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="lastname">Lastname</label>
            <input type="text" name="lastname" id="lastname" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
        </div>
        <div style="margin-bottom: 20px;">
            <input type="submit" value="Register" style="width: 100%; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer;">
        </div>
    </form>
</body>