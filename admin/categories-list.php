<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['category_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

    $queryCat = $db->prepare('DELETE FROM categories WHERE id = ?');
    $resultCat = $queryCat->execute(
        [
            $_GET['category_id']
        ]
    );

    $queryFaq = $db->prepare('DELETE FROM faq WHERE category_id = ?');
    $resultFaq = $queryFaq->execute(
        [
            $_GET['category_id']
        ]
    );
    //générer un message à afficher pour l'administrateur
    if($resultCat AND $resultFaq){
        $message = "Suppression efféctuée.";
    }
    else{
        $message = "Impossible de supprimer la séléction.";
    }
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM categories ORDER BY id DESC');
$categories = $query->fetchAll();
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
                <h4>Liste des catégories</h4>
                <a class="btn btn-primary" href="category-form.php">Ajouter une catégorie</a>
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
                    <th>Catégories</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php if($categories): ?>
                    <?php foreach($categories as $category): ?>

                        <tr>
                            <!-- htmlentities sert à écrire les balises html sans les interpréter -->
                            <td><?= htmlentities($category['id']); ?></td>
                            <td><?= htmlentities($category['name']); ?></td>
                            <td>
                                <a href="category-form.php?category_id=<?= $category['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                <a href="categories-list.php?category_id=<?= $category['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucune categorie enregistrée.
                <?php endif; ?>

                </tbody>
            </table>

        </section>

    </div>

</div>
</body>
</html>
