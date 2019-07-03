<?php

require_once 'tools/common.php';

$bill = null;
$date = null;
$amount = null;

$messages=[];

if (isset($_GET['bill_id']) AND $_GET['bill_id'] !== $getBill['id']) {
    header('Location:bills-list.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article
if(isset($_POST['save'])) {

    $allowed_extensions = array('pdf');
    //extension dufichier envoyé via le formulaire
    $my_file_extension = pathinfo($_FILES['billPdf']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['title'])) {
        $messages['title'] = 'Un titre est obligatoire';
    }

    if (empty($_POST['date'])) {
        $messages['date'] = 'Une date est obligatoire';
    }

    if (empty($_POST['amount'])) {
        $messages['amount'] = 'Un montant est obligatoire';
    }

    if ($_FILES['billPdf']['error']!==0) {
        $messages['billPdf'] = 'Un fichier est obligatoire';
    }

    if ($_FILES['billPdf']['error']==0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['billPdfExt'] = 'Mauvaise extention';
    }

    if (empty($_POST['user'])) {
        $messages['user'] = 'Un utilisateur est obligatoire';
    }

    if (empty($messages)) {

        do{
            $new_file_name = md5(rand());
            $destination = '../assets/img/bills/' . $new_file_name . '.' . $my_file_extension;
        } while(file_exists($destination));

        $result = move_uploaded_file($_FILES['billPdf']['tmp_name'], $destination);

        $query = $db->prepare('INSERT INTO bills (title, date, amount,user_id, billPdf) VALUES (?, ?, ?, ?, ?)');
        $newBill = $query->execute(
            [
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['date']),
                htmlspecialchars($_POST['amount']),
                htmlspecialchars($_POST['user']),
                htmlspecialchars($new_file_name . '.' . $my_file_extension)
            ]
        );

        if ($newBill) {
            $_SESSION['message'] = 'Nouvelle facture ajoutée';
            header("location:bills-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'ajout';
        }
    } else {
        $bill = $_POST['title'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
    }
}
//Si $_POST['update'] existe, cela signifie que c'est une mise à jour d'article
if(isset($_POST['update'])) {

    $getingBill = $db->prepare('SELECT billPdf FROM bills WHERE id=?');
    $getingBill->execute(array($_GET['bill_id']));
    $billGet = $getingBill->fetch();

    $allowed_extensions = array('pdf');
    //extension dufichier envoyé via le formulaire
    $my_file_extension = pathinfo($_FILES['billPdf']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['title'])) {
        $messages['title'] = 'Un titre est obligatoire';
    }

    if (empty($_POST['date'])) {
        $messages['date'] = 'Une date est obligatoire';
    }

    if (empty($_POST['amount'])) {
        $messages['amount'] = 'Un montant est obligatoire';
    }

    if (empty($_POST['user'])) {
        $messages['user'] = 'Un utilisateur est obligatoire';
    }

    if ($_FILES['billPdf']['error']!==0) {
        $messages['billPdf'] = 'Un fichier est obligatoire';
    }

    if ($_FILES['billPdf']['error']==0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['billPdfExt'] = 'Mauvaise extention';
    }

    if (empty($messages)) {

        do{
            $new_file_name = md5(rand());
            $destination = '../assets/img/bills/' . $new_file_name . '.' . $my_file_extension;
        } while(file_exists($destination));

        $result = move_uploaded_file($_FILES['billPdf']['tmp_name'], $destination);

        $query = $db->prepare('UPDATE bills SET
		title = :title,
		date = :date,
		amount = :amount,
		user_id = :user_id,
		billPdf = :billPdf
		WHERE id = :id'
        );

        //mise à jour avec les données du formulaire
        $resultBill = $query->execute([
            'title' => htmlspecialchars($_POST['title']),
            'date' => htmlspecialchars($_POST['date']),
            'amount' => htmlspecialchars($_POST['amount']),
            'user_id' => htmlspecialchars($_POST['user']),
            'billPdf' => htmlspecialchars($new_file_name . '.' . $my_file_extension),
            'id' => $_POST['id']
        ]);
        if ($resultBill) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:bills-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }

}
//si on modifie un article, on doit séléctionner l'article en question (id envoyé dans URL) pour pré-remplir le formulaire plus bas
if(isset($_GET['bill_id']) && isset($_GET['action']) && $_GET['action'] == 'edit'){

    $query = $db->prepare('SELECT * FROM bills WHERE id = ?');
    $query->execute(array($_GET['bill_id']));
    //$services contiendra les informations de l'article dont l'id a été envoyé en paramètre d'URL
    $getBill = $query->fetch();
}


?>

<!DOCTYPE HTML>
<html lang="fr">
<head>

    <title>Administration des factures</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-3">
                <!-- Si $article existe, on affiche "Modifier" SINON on affiche "Ajouter" -->
                <h4><?php if(isset($bill)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une facture</h4>
            </header>

            <div class="tab-content">

                <?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Si $article existe, chaque champ du formulaire sera pré-remplit avec les informations de l'article -->
                <form action="bill-form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="title">Intitulé de la facture :</label>
                        <input class="form-control" value="<?= isset($getBill)? htmlentities($getBill['title'])  : $bill; ?>" type="text" placeholder="Nommer la facture" name="title" id="title" />
                        <span class="text-danger"><?= isset($messages['title']) ? $messages['title'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="date">Date de la facture :</label>
                        <input class="form-control" value="<?= isset($getBill)? htmlentities($getBill['date'])  : $date; ?>" type="date" placeholder="Date" name="date" id="date" />
                        <span class="text-danger"><?= isset($messages['date']) ? $messages['date'] : ""; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="amount">Montant de la facture :</label>
                        <input class="form-control" value="<?= isset($getBill)? htmlentities($getBill['amount'])  : $amount; ?>" type="text" placeholder="Montant" name="amount" id="amount" />
                        <span class="text-danger"><?= isset($messages['amount']) ? $messages['amount'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="billPdf">Fichier PDF de la facture :</label>
                        <input class="form-control" value="<?= isset($getBill)? htmlentities($getBill['billPdf'])  : ''; ?>" type="file"  name="billPdf" id="billPdf" />
                        <?php if (isset($getBill['billPdf']) AND !empty($getBill['billPdf'])): ?>
                            <img class="img-fluid py-4" src="../assets/img/bills/<?= isset($bill) ? $getBill['billPdf'] : '';?>" alt="">
                        <?php endif; ?>
                        <span class="text-danger"><?= isset($messages['billPdf']) ? $messages['billPdf'] : ""; ?></span>
                        <span class="text-danger"><?= isset($messages['billPdfExt']) ? $messages['billPdfExt'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="user">Utilisateur :</label>
                        <select class="form-control" name="user" id="user">
                           <?php $queryUsers = $db->query('SELECT * FROM users');
                                 $resultUsers= $queryUsers->fetchAll();
                           ?>
                            <?php foreach ($resultUsers as $users): ?>
                            <option value="<?=  $users['id']; ?>" <?= isset($getBill) AND $getBill['user_id']==$users['id'] ? "selected" :  "" ; ?> ><?= $users['firstname']." ".$users['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger"><?= isset($messages['user']) ? $messages['user'] : ""; ?></span>
                    </div>

                    <div class="text-right">
                        <!-- Si $article existe, on affiche un lien de mise à jour -->
                        <?php if(isset($getBill)): ?>
                            <input class="btn btn-success" type="submit" name="update" value="Mettre à jour" />
                            <!-- Sinon on afficher un lien d'enregistrement d'un nouvel article -->
                        <?php else: ?>
                            <input class="btn btn-success" type="submit" name="save" value="Enregistrer" />
                        <?php endif; ?>
                    </div>

                    <!-- Si $article existe, on ajoute un champ caché contenant l'id de l'article à modifier pour la requête UPDATE -->
                    <?php if(isset($getBill)): ?>
                        <input type="hidden" name="id" value="<?= $getBill['id']; ?>" />
                    <?php endif; ?>

                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>