<?php

require_once 'tools/common.php';


$name = null;
$address = null;
$schedule = null;
$latitude = null;
$longitude = null;

$messages = [];



if (isset($_POST['save'])) {

    $allowed_extensions = array('jpg', 'jpeg', 'png');

    $my_file_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['name'])) {
        $messages['name'] = 'Le nom du service est obligatoire';
    }
    if (empty($_POST['address'])) {
        $messages['address'] = 'Une adresse est obligatoire';
    }
    if (empty($_POST['schedule'])) {
        $messages['schedule'] = 'Les horaires sont obligatoires';
    }
    if (empty($_POST['latitude'])) {
        $messages['latitude'] = 'La latitude est obligatoire';
    }
    if (empty($_POST['longitude'])) {
        $messages['longitude'] = 'La longitude est obligatoire';
    }
    if ($_FILES['img']['error'] !== 0) {
        $messages['img'] = 'Une image est obligatoire';
    }
    if ($_FILES['img']['error'] == 0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['imgExtension'] = 'Mauvaise extension';
    }

    if (empty($messages)) {
        do {
            $new_file_name = md5(rand());
            $destination = '../assets/img/services/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));
        $result = move_uploaded_file($_FILES['img']['tmp_name'], $destination);


        $query = $db->prepare('INSERT INTO services (name, address, img, schedule, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)');
        $newService = $query->execute(
            [
                htmlspecialchars($_POST['name']),
                htmlspecialchars($_POST['address']),
                htmlspecialchars($new_file_name . '.' . $my_file_extension),
                htmlspecialchars($_POST['schedule']),
                htmlspecialchars($_POST['latitude']),
                htmlspecialchars($_POST['longitude']),
            ]
        );

        if ($newService) {
            $_SESSION['message'] = 'Nouveau service ajouté';
            header("location:services-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'insertion';
        }
    } else {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $schedule = $_POST['schedule'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
    }
}


if (isset($_POST['update'])) {

    $getingImg = $db->prepare('SELECT img FROM services WHERE id=?');
    $getingImg->execute(array($_GET['service_id']));
    $imgGet = $getingImg->fetch();

    $allowed_extensions = array('jpg', 'jpeg', 'png');

    $my_file_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['name'])) {
        $messages['name'] = 'Le nom du service est obligatoire';
    }
    if (empty($_POST['address'])) {
        $messages['address'] = 'Une adresse est obligatoire';
    }
    if (empty($_POST['schedule'])) {
        $messages['schedule'] = 'Les horaires sont obligatoires';
    }
    if (empty($_POST['latitude'])) {
        $messages['latitude'] = 'La latitude est obligatoire';
    }
    if (empty($_POST['longitude'])) {
        $messages['longitude'] = 'La longitude est obligatoire';
    }
    if ($_FILES['img']['error'] !== 0) {
        $messages['img'] = 'Une image est obligatoire';
    }
    if ($_FILES['img']['error'] == 0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['imgExtension'] = 'Mauvaise extension';
    }

    if (empty($messages)) {

        do {
            $new_file_name = md5(rand());
            $destination = '../assets/img/services/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));
        $result = move_uploaded_file($_FILES['img']['tmp_name'], $destination);

        $query = $db->prepare('UPDATE services SET
		name = :name,
		address = :address,
		schedule = :schedule,
		latitude = :latitude,
		longitude = :longitude,
		img = :img
		WHERE id = :id'
        );


        $resultService = $query->execute([
            'name' => htmlspecialchars($_POST['name']),
            'address' => htmlspecialchars($_POST['address']),
            'schedule' => htmlspecialchars($_POST['schedule']),
            'latitude' => htmlspecialchars($_POST['latitude']),
            'longitude' => htmlspecialchars($_POST['longitude']),
            'img' => htmlspecialchars($new_file_name . '.' . $my_file_extension),
            'id' => $_POST['id']
        ]);
        if ($resultService) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:services-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }
}


if (isset($_GET['service_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM services WHERE id = ?');
    $query->execute(array($_GET['service_id']));

    $services = $query->fetch();
}

if (isset($_GET['service_id']) AND $_GET['service_id'] !== $services['id']) {
    header('location:services-list.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration des services</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-3">

                <h4><?php if (isset($services)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un service</h4>
            </header>

            <div class="tab-content">

                <?php if (isset($message)): ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>


                <?php if (isset($_GET['service_id'])): ?>
                <form action="service-form.php?service_id=<?= $services['id'] ?>&action=edit" method="post"
                      enctype="multipart/form-data">
                    <?php else: ?>
                    <form action="service-form.php" method="post" enctype="multipart/form-data">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input class="form-control"
                                   value="<?= isset($services) ? htmlentities($services['name']) : $name; ?>"
                                   type="text" placeholder="Nom du service" name="name" id="name"/>
                            <span class="text-danger"><?= isset($messages['name']) ? $messages['name'] : ""; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse :</label>
                            <input class="form-control"
                                   value="<?= isset($services) ? htmlentities($services['address']) : $address; ?>"
                                   type="text" placeholder="Adresse" name="address" id="address"/>
                            <span class="text-danger"><?= isset($messages['address']) ? $messages['address'] : ""; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="content">Horaires :</label>
                            <input class="form-control"
                                   value="<?= isset($services) ? htmlentities($services['schedule']) : $schedule; ?>"
                                   type="text" placeholder="Horaires" name="schedule" id="schedule"/>
                            <span class="text-danger"
                                  class="text-danger"><?= isset($messages['schedule']) ? $messages['schedule'] : ""; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="content">Latitude :</label>
                            <input class="form-control"
                                   value="<?= isset($services) ? htmlentities($services['latitude']) : $latitude; ?>"
                                   type="text" placeholder="Latitude" name="latitude" id="latitude"/>
                            <span class="text-danger"><?= isset($messages['latitude']) ? $messages['latitude'] : ""; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="content">Longitude :</label>
                            <input class="form-control"
                                   value="<?= isset($services) ? htmlentities($services['longitude']) : $longitude; ?>"
                                   type="text" placeholder="Longitude" name="longitude" id="longitude"/>
                            <span class="text-danger"><?= isset($messages['longitude']) ? $messages['longitude'] : ""; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="image">Image :</label>
                            <input class="form-control" type="file" name="img" id="image"/>
                            <?php if (isset($services) && $services['img']): ?>
                                <img class="img-fluid py-4" src="../assets/img/services<?= $services['img']; ?>"
                                     alt=""/>
                                <input type="hidden" name="current-image" value="<?= $services['img']; ?>"/>
                            <?php endif; ?>
                            <span class="text-danger"><?= isset($messages['img']) ? $messages['img'] : ""; ?></span>
                            <span class="text-danger"><?= isset($messages['imgExtension']) ? $messages['imgExtension'] : ""; ?></span>
                        </div>

                        <div class="text-right">

                            <?php if (isset($services)): ?>
                                <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>

                            <?php else: ?>
                                <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                            <?php endif; ?>
                        </div>


                        <?php if (isset($services)): ?>
                            <input type="hidden" name="id" value="<?= $services['id']; ?>"/>
                        <?php endif; ?>

                    </form>
        </section>
    </div>
</div>
</body>
</html>
