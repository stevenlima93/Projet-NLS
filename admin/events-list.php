<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['event_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $query = $db->prepare('SELECT image FROM events WHERE id = ?');
    $query->execute(array($_GET['event_id']));
    $imageToDelete = $query->fetch();

    $query = $db->prepare('DELETE FROM events WHERE id = ?');
    $result = $query->execute(
        [
            $_GET['event_id']
        ]
    );
    $path= '../assets/img/events/';
    unlink( $path . $imageToDelete['image']);

    //générer un message à afficher pour l'administrateur
    if($result){
        $_SESSION['message'] = "Suppression efféctuée.";
    }
    else{
        $_SESSION['message'] = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM events ORDER BY id DESC');
$events = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <title>Administration des évènements</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des évènements</h4>
                <a class="btn btn-primary" href="event-form.php">Ajouter un évènement</a>
            </header>

            <?php if(isset($_SESSION['message'])): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-success text-white p-2 mb-4">
                    <?= $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']) ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Dates</th>
                    <th>Titres</th>
                    <th>Résumer</th>
                    <th>Publication</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($events): ?>
                    <?php foreach($events as $event): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <th><?= htmlentities($event['id']); ?></th>
                            <td><?= htmlentities($event['created_at']); ?></td>
                            <td><?= htmlentities($event['title']); ?></td>
                            <td><?= htmlentities($event['summary']); ?></td>
                            <td><?= htmlentities($event['is_published'])==1 ? "OUI" : "NON"; ?></td>
                            <td>
                                <a href="event-form.php?event_id=<?= $event['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="events-list.php?event_id=<?= $event['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    Aucun évènement enregistré.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
