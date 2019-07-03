<?php

require_once 'tools/common.php';

$question = null;
$response = null;

$messages = [];


if (isset($_POST['save'])) {

    if (empty($_POST['question'])) {
        $messages['question'] = 'Une question est obligatoire';
    }
    if (empty($_POST['response'])) {
        $messages['response'] = 'Une réponse est obligatoire';
    }
    if (empty($messages)) {


        $query = $db->prepare('INSERT INTO faq (category_id, question, response) VALUES (?, ?, ?)');
        $newFaq = $query->execute(
            [
                htmlspecialchars($_POST['category_id']),
                htmlspecialchars($_POST['question']),
                htmlspecialchars($_POST['response']),
            ]
        );

        if ($newFaq) {
            $_SESSION['message'] = 'Nouvelle question ajoutée';
            header("location:faq-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'insertion';
        }
    } else {
        $question = $_POST['question'];
        $response = $_POST['response'];
    }
}

if (isset($_POST['update'])) {

    if (empty($_POST['question'])) {
        $messages['question'] = 'La question est obligatoire';
    }
    if (empty($_POST['response'])) {
        $messages['response'] = 'Une réponse est obligatoire';
    }
    if (empty($messages)) {

        $query = $db->prepare('UPDATE faq SET
        category_id = :category_id,
		question = :question,
		response = :response
		WHERE id = :id'
        );

        $resultFaq = $query->execute([
            'category_id' => htmlspecialchars($_POST['category_id']),
            'question' => htmlspecialchars($_POST['question']),
            'response' => htmlspecialchars($_POST['response']),
            'id' => $_POST['id']
        ]);
        if ($resultFaq) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:faq-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }

}
if (isset($_GET['faq_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM faq WHERE id = ?');
    $query->execute(array($_GET['faq_id']));

    $faq = $query->fetch();
}

if (isset($_GET['faq_id']) AND $_GET['faq_id'] !== $faq['id']) {
    header('location:faq-list.php');
    exit;
}

?>

<!DOCTYPE HTML>
<html lang="fr">
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
            <header class="pb-3">

                <h4><?php if (isset($faq)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une question/réponse</h4>
            </header>

            <div class="tab-content">

                <?php if (isset($message)): ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>


                <form action="faq-form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Question :</label>
                        <input class="form-control"
                               value="<?= isset($faq) ? htmlentities($faq['question']) : $question; ?>" type="text"
                               placeholder="Question" name="question" id="question"/>
                        <span class="text-danger"><?= isset($messages['question']) ? $messages['question'] : ""; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="response">Réponse :</label>
                        <input class="form-control"
                               value="<?= isset($faq) ? htmlentities($faq['response']) : $response; ?>" type="text"
                               placeholder="Réponse" name="response" id="response"/>
                        <span class="text-danger"><?= isset($messages['response']) ? $messages['response'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Catégorie :</label>

                        <select class="form-control" name="category_id" id="category_id">
                            <?php $getCategories = $db->query('SELECT * FROM categories');
                            $categories = $getCategories->fetchAll(); ?>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>" <?php isset($faq) AND $faq['category_id'] == $category['id'] ? 'selected' : ''; ?> ><?= $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger"><?= isset($messages['response']) ? $messages['response'] : ""; ?></span>
                    </div>


                    <div class="text-right">

                        <?php if (isset($faq)): ?>
                            <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>

                        <?php else: ?>
                            <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                        <?php endif; ?>
                    </div>


                    <?php if (isset($faq)): ?>
                        <input type="hidden" name="id" value="<?= $faq['id']; ?>"/>
                    <?php endif; ?>

                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>