<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['faq_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $query = $db->prepare('DELETE FROM faq WHERE id = ?');
    $result = $query->execute(
        [
            $_GET['faq_id']
        ]
    );
    //générer un message à afficher pour l'administrateur
    if($result){
        $message = "Suppression efféctuée.";
    }
    else{
        $message = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM faq ORDER BY id DESC');
$faq = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <title>Administration de la FAQ</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des question/réponse</h4>
                <a class="btn btn-primary" href="faq-form.php">Ajouter une question/réponse</a>
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
                    <th>Questions</th>
                    <th>Réponses</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($faq): ?>
                    <?php foreach($faq as $newFaq): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <td><?= htmlentities($newFaq['id']); ?></td>
                            <td><?= htmlentities($newFaq['question']); ?></td>
                            <td><?= htmlentities($newFaq['response']); ?></td>
                            <td>
                                <a href="faq-form.php?faq_id=<?= $newFaq['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="faq-list.php?faq_id=<?= $newFaq['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucun service enregistré.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
