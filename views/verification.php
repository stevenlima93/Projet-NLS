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
<main class="mainContainer flex align ">
    <div class="containerForm">
        <div>
            <form action="index.php?page=verification" method="post" enctype="multipart/form-data">

            <h3 class="blockTitle bgcBluefresh">Mes informations</h3>

            <p class="info">Verirfier si vos informations sont correctes</p>

            <div class="info">
                <label for="name">Nom : </label>
                <p><?= $infoUser['name']; ?></p>
            </div>

            <div class="info">
                <label for="name">Prénom : </label>
                <p><?= $infoUser['firstname']; ?></p>
            </div>

            <div class="info">
                <label for="birthdate"> Date de naissance : </label>
                <p><?= $infoUser['birthdate']; ?></p>
            </div>

            <div class="info">
                <label for="address">Adresse : </label>
                <p><?= $infoUser['address']; ?></p>
            </div>

            <div class="info">
                <label for="number">Numéro : </label>
                <p><?= $infoUser['number']; ?></p>
            </div>

        </div>
    </div>

    <div class="choice">
        <button class="btnForm" name="btnGood" value="1" type="submit">Valider</button>

        <button  class="btnForm" name="btnBad" value="1" type="submit">Il y a une erreur</button>
        </form>
    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
</body>
</html>