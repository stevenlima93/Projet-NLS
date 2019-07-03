<?php

require_once 'tools/common.php';

if(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $queryPdf = $db->prepare('SELECT * FROM bills WHERE user_id=?');
    $queryPdf->execute(array($_GET['user_id']));
    $pdfName= $queryPdf->fetchAll();

    if ($pdfName){
        foreach ($pdfName as $bills){

            unlink("../assets/img/bills/".$bills['billPdf']);
        }
    }

    $querySpprbills= $db->prepare('DELETE FROM bills WHERE user_id=?');
    $result= $querySpprbills->execute(array($_GET['user_id']));


    $query = $db->prepare('DELETE FROM users WHERE id = ?');
    $result = $query->execute(
        [
            $_GET['user_id']
        ]
    );

    if($result){
        $_SESSION['message'] = "Suppression efféctuée.";
    }
    else{
        $_SESSION['message'] = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM users ORDER BY id DESC');
$users = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <title>Administration des users</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des utilisateurs</h4>
                <a class="btn btn-primary" href="user-form.php">Ajouter un utilisateur</a>
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
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($users): ?>
                    <?php foreach($users as $user): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <th><?= htmlentities($user['id']); ?></th>
                            <td><?= htmlentities($user['name']); ?></td>
                            <td><?= htmlentities($user['firstname']); ?></td>
                            <td><?= htmlentities($user['address']); ?></td>
                            <td>
                                <a href="user-form.php?user_id=<?= $user['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="users-list.php?user_id=<?= $user['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    Aucun utilisateur enregistré.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
