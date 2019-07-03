<!DOCTYPE HTML>
<html lang="fr">
<head>
    <?php require 'views/partials/head-assets.php'; ?>

    <title>NLS</title>

</head>
<body>
<div id="homeHeader">
    <?php require 'views/partials/headerV2.php'; ?>
</div>
<main class="mainContainer flex flexDirect">
    <h1>Équipements publiques</h1>
    <div class="flex flexWrap justifyCont align">
        <?php foreach ($services as $service): ?>
            <a href="#map">
                <div class="service initMap" data-latitude="<?= $service['latitude']; ?>"
                     data-longitude="<?= $service['longitude']; ?>">
                    <img class="cover" src="assets/img/services/<?= $service["img"]; ?>" alt="">
                    <div class="serviceName flexDirect">
                        <h3><?= $service["name"]; ?></h3>
                        <p><?= $service["address"]; ?></p>
                        <p><?= $service["schedule"]; ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</main>
<div id="map"></div>
<?php require 'views/partials/footer.php'; ?>

<script async defer
        type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeKLAUA2xcY4iS0Co_9lgEl-SQJXH0WfA&libraries=places&callback=initMap"></script>
<script rel="stylesheet" type="text/javascript" src="./js/services.js"></script>
</body>
</html>


