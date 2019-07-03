<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['ln_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $query = $db->prepare('DELETE FROM legal_notices WHERE id = ?');
    $result = $query->execute(
        [
            $_GET['ln_id']
        ]
    );
    //générer un message à afficher pour l'administrateur
    if($result){
        $_SESSION['message'] = "Suppression efféctuée.";
    }
    else{
        $_SESSION['message'] = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM legal_notices ORDER BY id DESC');
$ln = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <title>Administration des mentions légales</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des mentions légales</h4>
                <a class="btn btn-primary" href="legal-notices-form.php">Ajouter une mention</a>
            </header>

            <?php if(isset($_SESSION['message'])): ?>
                <div class="bg-success text-white p-2 mb-4">
                    <?= $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']) ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Titres</th>
                    <th>Mentions</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($ln): ?>
                    <?php foreach($ln as $newLn): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <td><?= htmlentities($newLn['id']); ?></td>
                            <td><?= htmlentities($newLn['title']); ?></td>
                            <td><?= htmlentities($newLn['content']); ?></td>
                            <td>
                                <a href="legal-notices-form.php?ln_id=<?= $newLn['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="legal-notices-list.php?ln_id=<?= $newLn['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucune mentions légales enregistrées.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
