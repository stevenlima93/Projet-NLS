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
<main class="mainContainer flex justifyCont align flexDirect">
    <h1>Nous contacter</h1>

    <div class="containerForm">
        <form class="form" action="index.php?page=contact" method="post" enctype="multipart/form-data">
            <div>
                <label for="name">Nom : </label>
                <input class="inputForm" type="text" placeholder="Nom" name="name" id="name">
            </div>

            <div>
                <label for="firstname">Prénom : </label>
                <input class="inputForm" type="text" placeholder="Prénom" name="firsname" id="firstname">
            </div>

            <div>
                <label for="email">Email : </label>
                <input class="inputForm" type="email" placeholder="Email" name="email" id="email">
            </div>

            <div>
                <label for="subject">Objet : </label>
                <input class="inputForm" type="text" placeholder="Objet de votre message" name="subject" id="subject">
            </div>

            <div>
                <label for="message">Message :</label>
                <textarea class="textarea"></textarea>
            </div>

            <button class="btnForm" type="submit">Envoyé</button>
            <?= isset($errorLog) ? $errorLog : ''; ?>
        </form>
    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
</body>
</html>