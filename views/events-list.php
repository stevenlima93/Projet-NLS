<!DOCTYPE HTML>
<html lang="fr">
<head>
    <?php require 'views/partials/head-assets.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <title>NLS</title>

</head>
<body>
<div id="homeHeader">
    <?php require 'views/partials/headerV2.php'; ?>
</div>
<main class="mainContainer">
    <h1>Évènement</h1>

    <div id="containerEvents" class="flex flexDirect align">
        <form style="position: relative" method="post">

            <input class="datePiker" name="datePiker" id="myID">
            <button class="btnForm" type="submit" name=sendDate"">Afficher par date</button>
            <button class="btnForm" type="submit" name="allEvents">Tout afficher</button>

        </form>
        <?php foreach ($events

        as $event): ?>
        <div class="eventBlock">
            <div>
                <a class="eventInfo" href="index.php?page=event&event_id=<?= $event['id']; ?>">
                    <img class="cover" src="assets/img/events/<?= $event['image']; ?>" alt="">
                    <div class="eventName" id='eventName'>
                        <h3><?= $event['title']; ?></h3>
                        <p><?= $event['summary']; ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

</main>
<?php require 'views/partials/footer.php'; ?>

<script>flatpickr("#myID", {});</script>
</body>
</html>


