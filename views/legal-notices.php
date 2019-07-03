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
<main class="mainContainer">

    <div class="container">
        <h3>Mention légales</h3>
        <?php if ($legalNotices): ?>
        <?php foreach ($legalNotices as $legalNotice): ?>
        <h4> <?= $legalNotice["title"]; ?> </h4>
        <p> <?= $legalNotice["content"]; ?> </p>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
</body>
