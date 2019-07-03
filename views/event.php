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
<main class="mainContainer flex flexDirect">

    <h1>Évènement</h1>
    <div class="container">
        <img class="eventImg" src="./assets/img/events/<?= $event['image']; ?>" alt="">
        <h2><?= strftime("%A %e %B %Y", strtotime($event['created_at'])); ?></h2>
        <p class="eventContent"><?= $event['content']; ?></p>
        <?php if ($event['video']): ?>
            <div class="video"><?= $event['video']; ?></div>
        <?php endif; ?>
        <?php if ($images): ?>
            <div class="direction-row">
                <?php foreach ($images as $image): ?>
                    <div class="name">
                        <div class="imgGallery">
                            <img class="nameImg" src="./assets/img/events/<?= $image['caption']; ?>" alt="">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
</body>
</html>