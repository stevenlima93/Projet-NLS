<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>NLS</title>

    <?php require 'views/partials/head-assets.php'; ?>
</head>
<body>
<div id="homeHeader">
    <?php require 'views/partials/headerV2.php'; ?>
</div>
<main class="mainContainer flex flexDirect align">
        <h1>Connectez-vous</h1>

        <div class="containerForm">
                <form action="index.php?page=connexion" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label for="email">Email : </label>
                            <input type="email" placeholder="Email" name="email" id="email">
                        </li>
                        <li>
                            <label for="password">Mot de passe : </label>
                            <input type="password" placeholder="Mot de passe" name="password" id="password">
                        </li>

                            <button class="btnForm" type="submit" name="connexion">Connexion</button>
                        <?= isset($errorLog) ? $errorLog : ''; ?>
                  </ul>
            </form>
        </div>

</main>
<?php require 'views/partials/footer.php'; ?>
</body>
</html>