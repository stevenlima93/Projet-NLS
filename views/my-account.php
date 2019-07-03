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
    <h1>Mon espace</h1>
    <div class="containerForm">
        <div>
            <form action="index.php?page=verification" method="post" enctype="multipart/form-data">

                <h3 class="blockTitle bgcBluefresh">Mes informations</h3>

                <div class="userInfo">

                    <div class="information">
                        <label for="name">Nom : </label>
                        <p><?= $infoUser['name']; ?></p>
                    </div>

                    <div class="information">
                        <label for="name">Prénom : </label>
                        <p><?= $infoUser['firstname']; ?></p>
                    </div>

                    <div class="information">
                        <label for="birthdate"> Date de naissance : </label>
                        <p><?= $infoUser['birthdate']; ?></p>
                    </div>

                    <div class="information">
                        <label for="address">Adresse : </label>
                        <p><?= $infoUser['address']; ?></p>
                    </div>

                    <div class="information">
                        <label for="number">Numéro de téléphone : </label>
                        <p><?= $infoUser['number']; ?></p>
                    </div>

                    <div class="information">
                        <label for="email">Email : </label>
                        <p><?= $infoUser['email']; ?></p>
                    </div>

                    <div class="information">
                        <label for="email"> : </label>
                        <p><?= $infoUser['email']; ?></p>
                    </div>

                    <a href="index.php?page=contact">Signaler une erreur</a>
                </div>
        </div>
    </div>

    <div class="charge">
        <h3 class="blockTitle bgcBluefresh">Mes factures</h3>
        <div class="flex justifyCont">
            <div class="cell">
                <p class="cellName bgcBlue">Intitulé</p>
            </div>
            <div class="cell">
                <p class="cellName bgcBlue">Date</p>
            </div>
            <div class="cell">
                <p class="cellName bgcBlue">Montant</p>
            </div>
            <div class="cell">
                <p class="cellName bgcBlue">Fichiers</p>
            </div>
        </div>
        <?php if ($bills) : ?>
            <?php foreach ($bills as $bill): ?>
                <div class="flex justifyCont">
                    <div class="cell">
                        <p class="cellContent"><?= $bill['title']; ?></p>
                    </div>

                    <div class="cell">
                        <p class="cellContent"><?= $bill['date']; ?></p>
                    </div>

                    <div class="cell">
                        <p class="cellContent"><?= $bill['amount']; ?>€</p>
                    </div>

                    <div class="cell">
                        <p class="cellContent"><a target="_blank" href="assets/img/bills/<?= $bill['billPdf']; ?>">PDF</a></p>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
</body>
