<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['bill_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $queryPdf = $db->prepare('SELECT billPdf FROM bills WHERE id=?');
    $queryPdf->execute(array($_GET['bill_id']));
    $pdfName= $queryPdf->fetch();

    $queryBills = $db->prepare('DELETE FROM bills WHERE id = ?');
    $resultBills = $queryBills->execute(
        [
            $_GET['bill_id']
        ]
    );

    unlink("../assets/img/bills/".$pdfName['billPdf']);

    //générer un message à afficher pour l'administrateur
    if($resultBills){
        $_SESSION['message'] = "Suppression efféctuée.";
    }
    else{
        $_SESSION['message'] = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM bills ORDER BY id DESC');
$bills = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <title>Administration des catégories</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des factures</h4>
                <a class="btn btn-primary" href="bill-form.php">Ajouter une facture</a>
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
                    <th>Intitulés</th>
                    <th>Dates</th>
                    <th>Montants</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($bills): ?>
                    <?php foreach($bills as $bill): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <td><?= htmlentities($bill['id']); ?></td>
                            <td><?= htmlentities($bill['title']); ?></td>
                            <td><?= htmlentities($bill['date']); ?></td>
                            <td><?= htmlentities($bill['amount']); ?></td>
                            <td>
                                <a href="bill-form.php?bill_id=<?= $bill['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="bills-list.php?bill_id=<?= $bill['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucune factures enregistrées.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
