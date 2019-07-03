<?php

require_once 'tools/common.php';

$title = null;
$content = null;

$messages = [];


if (isset($_POST['save'])) {

    if (empty($_POST['title'])) {
        $messages['title'] = 'Un titre est obligatoire';
    }
    if (empty($_POST['content'])) {
        $messages['content'] = 'Un contenu est obligatoire';
    }
    if (empty($messages)) {


        $query = $db->prepare('INSERT INTO legal_notices (title, content) VALUES (?, ?)');
        $newLn = $query->execute(
            [
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['content']),
            ]
        );

        if ($newLn) {
            $_SESSION['message'] = 'Nouvelle mention ajoutée';
            header("location:legal-notices-list.php");
        } else {
            $_SESSION['message'] = 'Echec de l\'insertion';
        }
    } else {
        $question = $_POST['title'];
        $response = $_POST['content'];
    }
}

if (isset($_POST['update'])) {

    if (empty($_POST['title'])) {
        $messages['title'] = 'Le titre est obligatoire';
    }
    if (empty($_POST['content'])) {
        $messages['content'] = 'Le contenu est obligatoire';
    }
    if (empty($messages)) {

        $query = $db->prepare('UPDATE legal_notices SET
		title = :title,
		content = :content
		WHERE id = :id'
        );


        $resultLn = $query->execute([
            'title' => htmlspecialchars($_POST['title']),
            'content' => htmlspecialchars($_POST['content']),
            'id' => $_POST['id']
        ]);
        if ($resultLn) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:legal-notices-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }

}

if (isset($_GET['ln_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM legal_notices WHERE id = ?');
    $query->execute(array($_GET['ln_id']));

    $legalNotices = $query->fetch();
}

if (isset($_GET['ln_id']) AND $_GET['ln_id'] !== $legalNotices['id']) {
    header('location:legal-notices-list.php');
    exit;
}

?>

<!DOCTYPE HTML>
<html lang="fr">
<head>

    <title>Administration des mentions légal</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-3">

                <h4><?php if (isset($legalNotices)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une mentions</h4>
            </header>

            <div class="tab-content">

                <?php if (isset($message)): ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>


                <form action="legal-notices-form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="title">Titre :</label>
                        <input class="form-control"
                               value="<?= isset($legalNotices) ? htmlentities($legalNotices['title']) : $title; ?>"
                               type="text" placeholder="Le titre de la mention" name="title" id="title"/>
                        <span class="text-danger"><?= isset($messages['title']) ? $messages['title'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="content">Contenu :</label>
                        <input class="form-control"
                               value="<?= isset($legalNotices) ? htmlentities($legalNotices['content']) : $content; ?>"
                               type="text" placeholder="Contenu" name="content" id="content"/>
                        <span class="text-danger"><?= isset($messages['content']) ? $messages['content'] : ""; ?></span>
                    </div>


                    <div class="text-right">

                        <?php if (isset($legalNotices)): ?>
                            <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>

                        <?php else: ?>
                            <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                        <?php endif; ?>
                    </div>


                    <?php if (isset($legalNotices)): ?>
                        <input type="hidden" name="id" value="<?= $legalNotices['id']; ?>"/>
                    <?php endif; ?>

                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>