<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>NLS-Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <?php require 'views/partials/head-assets.php'; ?>
</head>
<body>
<div id="homeHeader">
    <?php require 'views/partials/headerV2.php'; ?>
</div>
<main class="mainContainer">
    <div class="w3-content w3-display-container">
        <?php foreach ($events as $event): ?>
            <div class="w3-display-container mySlides">
                <img class="cover" src="assets/img/events/<?= $event['image']; ?>" style="width:100%; height: 300px;">
                <div style="width:100% ; background-color:rgb(230, 239, 255)" class="w3-display-bottommiddle w3-large w3-container">
                    <h2><?= $event['title']; ?></h2>
                </div>
            </div>
        <?php endforeach; ?>


        <button class="w3-button w3-display-left w3-light-grey w3-xlarge" onclick="plusDivs(-1)">&#10094;</button>
        <button class="w3-button w3-display-right w3-light-grey w3-xlarge" onclick="plusDivs(1)">&#10095;</button>

    </div>

    <div class="container">
        <h1>Bienvenue à Noisy</h1>

        <p class="txtContent">Commune décorée de la Croix de guerre avec palmes au titre de la Seconde Guerre mondiale,
            car son centre ferroviaire a été un des lieux les plus actifs de la résistance française et le courage de
            ses habitants victimes d'un terrible bombardement dans la nuit du 18 avril 1944 a ainsi été honoré.
            L'offensive aérienne de la Royal Air Force, destinée à détruire l'important centre ferroviaire de l'Est
            parisien, avait été relayée par le message de la BBC « les haricots verts sont secs ».<br>
            D'autre part son surnom « le sec » permet de distinguer Noisy des 7 autres communes du même nom en France,
            c’est une référence à la sécheresse, l'aridité du sol.</p>
    </div>

    <img class="imgContent responsiveImg" src="assets/img/nls_chemin_de_fer.jpg" alt="carte postal d'époque gare de noisy">
    <img class="imgContent responsiveImg" src="assets/img/nls_tramways.jpeg" alt="carte postal d'époque tramway">
    <img class="imgContent responsiveImg" src="assets/img/cpa-place-mairie.jpg" alt="carte postal d'époque mairie">

</main>
<?php require 'views/partials/footer.php'; ?>

<script rel="stylesheet" type="text/javascript" src="./js/slider.js"></script>

</body>
</html>