<?php
// Définir des variables pour les styles
$textColor = '#333';
$backgroundColor = '#f4f4f4';
$buttonColor = '#007bff';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: <?= $textColor ?>;
            background-color: <?= $backgroundColor ?>;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: <?= $buttonColor ?>;
            text-align: center;
        }

        p {
            text-align: center;
        }
    </style>
</head>


<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px;  ">
    <h1>Stupid Blog</h1>
    <p>Le blog qui suis presque tous les principes SOLID</p>
</body>

</html>