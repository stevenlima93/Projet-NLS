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
    <h1>FAQ</h1>
    <div class="container">

        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <h3><?= $category['nameCat'] ?></h3>
                <?php foreach ($faq as $questRep): ?>
                    <?php if ($category['id'] == $questRep['category_id']): ?>
                        <button class="collapsible"><?= $questRep['question']; ?></button>
                        <div class="faqResponse">
                            <p><?= $questRep['response']; ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Pas de question disponible</p>
        <?php endif; ?>

    </div>
</main>
<?php require 'views/partials/footer.php'; ?>
<script rel="stylesheet" src="js/faq.js"></script>
</body>
</html>