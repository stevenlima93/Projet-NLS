<?php

require_once 'tools/common.php';

$messages = [];

if (isset($_POST['save'])) {

    if (empty($_POST['name'])) {
        $messages['name'] = 'Un nom de categorie est obligatoire';
    }

    if (empty($messages)) {
        $query = $db->prepare('INSERT INTO categories (name) VALUES (?)');
        $newCategory = $query->execute(
            [
                htmlspecialchars($_POST['name']),
            ]
        );

        if ($newCategory) {
            $_SESSION['message'] = 'Nouvelle categorie ajoutée';
            header("location:categories-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'ajout';
        }
    } else {
        $category = $_POST['name'];
    }
}
if (isset($_POST['update'])) {

    if (empty($_POST['name'])) {
        $messages['name'] = 'Une categories est obligatoire';
    }

    if (empty($messages)) {

        $query = $db->prepare('UPDATE categories SET
		name = :name
		WHERE id = :id'
        );

        $resultCat = $query->execute([
            'name' => htmlspecialchars($_POST['name']),
            'id' => $_POST['id']
        ]);
        if ($resultCat) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:categories-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }

}
if (isset($_GET['category_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM categories WHERE id = ?');
    $query->execute(array($_GET['category_id']));
    $getCat = $query->fetch();
}

if (isset($_GET['category_id']) AND $_GET['category_id'] !== $getCat['id']) {
    header('location:categories-list.php');
    exit;
}

?>

<!DOCTYPE HTML>
<html lang="fr">
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
            <header class="pb-3">

                <h4><?php if (isset($category)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une catégorie</h4>
            </header>

            <div class="tab-content">

                <?php if (isset($message)): ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="category-form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Nom de la catégorie :</label>
                        <input class="form-control"
                               value="<?= isset($getCat) ? htmlentities($getCat['name']) : $category; ?>" type="text"
                               placeholder="Nommer la catégorie" name="name" id="name"/>
                        <span class="text-danger"><?= isset($messages['name']) ? $messages['name'] : ""; ?></span>
                    </div>

                    <div class="text-right">
                        <?php if (isset($getCat)): ?>
                            <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>
                        <?php else: ?>
                            <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($getCat)): ?>
                        <input type="hidden" name="id" value="<?= $getCat['id']; ?>"/>
                    <?php endif; ?>

                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>