<?php

require_once 'tools/common.php';

$date = null;
$title = null;
$summary = null;
$content = null;
$video = null;
$published = null;

$messages = [];


if (isset($_POST['save'])) {

    $allowed_extensions = array('jpg', 'jpeg', 'png');

    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['date'])) {
        $messages['date'] = 'La date de l\'évènement est obligatoire';
    }
    if (empty($_POST['title'])) {
        $messages['title'] = 'Un titre est obligatoire';
    }
    if (empty($_POST['summary'])) {
        $messages['summary'] = 'Le résumer est obligatoire';
    }
    if (empty($_POST['content'])) {
        $messages['content'] = 'Le contenu est obligatoire';
    }
    if ($_FILES['image']['error'] !== 0) {
        $messages['image'] = 'Une image est obligatoire';
    }
    if ($_FILES['image']['error'] == 0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['imageExtension'] = 'Mauvaise extension';
    }
    if (empty($messages)) {
        do {
            $new_file_name = md5(rand());
            $destination = '../assets/img/events/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));
        $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);


        $query = $db->prepare('INSERT INTO events (created_at, title, summary, content, is_published, image, video) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $newService = $query->execute(
            [
                htmlspecialchars($_POST['date']),
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['summary']),
                htmlspecialchars($_POST['content']),
                htmlspecialchars($_POST['is_published']),
                htmlspecialchars($new_file_name . '.' . $my_file_extension),
                $_POST['video']
            ]
        );

        if ($newService) {
            $_SESSION['message'] = 'Nouvel évènement ajouté';
            header("location:events-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'insertion';
        }
    } else {
        $date = $_POST['date'];
        $title = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $published = $_POST['is_published'];
        $video = $_POST['video'];
    }
}

if (isset($_POST['update'])) {

    $getingImg = $db->prepare('SELECT image FROM events WHERE id=?');
    $getingImg->execute(array($_GET['event_id']));
    $imgGet = $getingImg->fetch();

    $allowed_extensions = array('jpg', 'jpeg', 'png');

    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    if (empty($_POST['date'])) {
        $messages['date'] = 'La date de l\'évènement est obligatoire';
    }
    if (empty($_POST['title'])) {
        $messages['title'] = 'Un titre est obligatoire';
    }
    if (empty($_POST['summary'])) {
        $messages['summary'] = 'Le résumer est obligatoire';
    }
    if (empty($_POST['content'])) {
        $messages['content'] = 'Le contenu est obligatoire';
    }
    if ($_FILES['image']['error'] !== 0) {
        $messages['image'] = 'Une image est obligatoire';
    }
    if ($_FILES['image']['error'] == 0 AND !in_array($my_file_extension, $allowed_extensions)) {
        $messages['imageExtension'] = 'Mauvaise extension';
    }

    if (empty($messages)) {

        do {
            $new_file_name = md5(rand());
            $destination = '../assets/img/events/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));
        $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

        $query = $db->prepare('UPDATE events SET
		created_at = :created_at,
		title = :title,
		summary = :summary,
		content = :content,
		image = :image,
		video = :video
		WHERE id = :id'
        );


        $resultEvent = $query->execute([
            'created_at' => htmlspecialchars($_POST['date']),
            'title' => htmlspecialchars($_POST['title']),
            'summary' => htmlspecialchars($_POST['summary']),
            'content' => htmlspecialchars($_POST['content']),
            'image' => htmlspecialchars($new_file_name . '.' . $my_file_extension),
            'video' => $_POST['video'],
            'id' => $_POST['id']
        ]);
        if ($resultEvent) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:index.php?page=events-list');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    } else {
        $date = $_POST['date'];
        $title = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $published = $_POST['is_published'];
        $video = $_POST['video'];
    }
}

if (isset($_POST['add_image'])) {

    $allowed_extensions = array('jpg', 'jpeg', 'png');
    $my_file_extension = pathinfo($_FILES['images']['name'], PATHINFO_EXTENSION);

    if (!in_array($my_file_extension, $allowed_extensions)) {
        $messages['ext'] = "Mauvaise extention";
    } else {

        do {
            $new_file_name = md5(rand());
            $destination = '../assets/img/events/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));

        $result = move_uploaded_file($_FILES['images']['tmp_name'], $destination);

        $query = $db->prepare('INSERT INTO images (name, caption, event_id) VALUES (?, ?, ?)');
        $newImage = $query->execute([
            htmlspecialchars($new_file_name . '.' . $my_file_extension),
            htmlspecialchars($_POST['caption']),
            $_POST['event_id']
        ]);
        if ($newImage) {
            header('location:index.php?page=events-list');
            exit;
        }
    }
}

if (isset($_POST['delete_image'])) {
    $query = $db->prepare('SELECT name FROM images WHERE id = ?');
    $query->execute([$_POST['img_id']]);
    $ImgToUnlink = $query->fetch();

    if ($ImgToUnlink) {

        unlink('../assets/img/events/' . $ImgToUnlink['name']);

        $queryDelete = $db->prepare('DELETE FROM images WHERE id=?');
        $queryDelete->execute([$_POST['img_id']]);

        $imgMessage = "Image Supprimée avec succès !";
    }
}


if (isset($_GET['event_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM images WHERE id = ?');
    $query->execute(array($_GET['event_id']));
    $images = $query->fetchAll();

    $query = $db->prepare('SELECT * FROM events WHERE id = ?');
    $query->execute(array($_GET['event_id']));

    $event = $query->fetch();
}

if (isset($_GET['event_id']) AND $_GET['event_id'] !== $event['id']) {
    header('location:events-list.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

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

                <h4><?php if (isset($event)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un évènement</h4>
            </header>


            <?php if (isset($message)): ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>


            <ul class="nav nav-tabs justify-content-center nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php if (isset($_POST['save']) || isset($_POST['update']) || (!isset($_POST['add_image']) && !isset($_POST['delete_image']))): ?>active<?php endif; ?>"
                       data-toggle="tab" href="#infos" role="tab">Infos</a>
                </li>
                <?php if (isset($event)): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if (isset($_POST['add_image']) || isset($_POST['delete_image'])): ?>active<?php endif; ?>"
                           data-toggle="tab" href="#images" role="tab">Images</a>
                    </li>
                <?php endif; ?>
            </ul>


            <?php if (isset($_GET['event_id'])): ?>
            <div class="tab-content">
                <div class="tab-pane container-fluid <?php if (isset($_POST['save']) || isset($_POST['update']) || (!isset($_POST['add_image']) && !isset($_POST['delete_image']))): ?>active<?php endif; ?>"
                     id="infos" role="tabpanel">

                    <form action="event-form.php?event_id=<?= $event['id'] ?>&action=edit" method="post"
                          enctype="multipart/form-data">
                        <?php else: ?>
                        <form action="event-form.php" method="post" enctype="multipart/form-data">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="date">Date de l'évènement :</label>
                                <input class="form-control"
                                       value="<?= isset($event) ? htmlentities($event['created_at']) : $date; ?>"
                                       type="date" name="date" id="date"/>
                                <span class="text-danger"><?= isset($messages['date']) ? $messages['date'] : ""; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="title">Titre :</label>
                                <input class="form-control"
                                       value="<?= isset($event) ? htmlentities($event['title']) : $title; ?>"
                                       type="text" placeholder="Titre de l'évènement" name="title" id="title"/>
                                <span class="text-danger"><?= isset($messages['title']) ? $messages['title'] : ""; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="content">Résumer :</label>
                                <input class="form-control"
                                       value="<?= isset($event) ? htmlentities($event['summary']) : $summary; ?>"
                                       type="text" placeholder="Résumer l'évènement" name="summary" id="summary"/>
                                <span class="text-danger"
                                      class="text-danger"><?= isset($messages['summary']) ? $messages['summary'] : ""; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="content">Content :</label>
                                <textarea class="form-control" type="text" placeholder="Contenu de l'évènement"
                                          name="content" rows="10"
                                          id="content"><?= isset($event) ? htmlentities($event['content']) : $content; ?></textarea>
                                <span class="text-danger"
                                      class="text-danger"><?= isset($messages['content']) ? $messages['content'] : ""; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="video">Vidéo :</label>
                                <input class="form-control" type="text" name="video" id="video"
                                       value="<?= isset($event) ? htmlentities($event['video']) : $video; ?>"/>
                            </div>

                            <div class="form-group">
                                <label for="image">Image :</label>
                                <input class="form-control" type="file" name="image" id="image"/>
                                <?php if (isset($event) && $event['image']): ?>
                                    <img class="img-fluid py-4" src="../assets/image/events<?= $event['image']; ?>"
                                         alt=""/>
                                    <input type="hidden" name="current-image" value="<?= $event['image']; ?>"/>
                                <?php endif; ?>
                                <span class="text-danger"
                                      class="text-danger"><?= isset($messages['image']) ? $messages['image'] : ""; ?></span>
                                <span class="text-danger"
                                      class="text-danger"><?= isset($messages['imageExtension']) ? $messages['imageExtension'] : ""; ?></span>
                            </div>


                            <div class="form-group">
                                <label for="is_published">Publié l'évènement ?</label>
                                <select class="form-control" name="is_published" id="is_published">
                                    <option value="0"
                                            <?php if (isset($event) && $event['is_published'] == 0): ?>selected<?php endif; ?>>
                                        Non
                                    </option>
                                    <option value="1"
                                            <?php if (isset($event) && $event['is_published'] == 1): ?>selected<?php endif; ?>>
                                        Oui
                                    </option>
                                </select>
                            </div>

                            <div class="text-right">
                                <?php if (isset($event)): ?>
                                    <input class="btn btn-success" type="submit" name="update"
                                           value="Mettre à jour"/>
                                <?php else: ?>
                                    <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($event)): ?>
                                <input type="hidden" name="id" value="<?= $event['id']; ?>"/>
                            <?php endif; ?>

                        </form>
                </div>

                <?php if (isset($event)): ?>
                    <div class="tab-pane container-fluid <?php if (isset($_POST['add_image']) || isset($_POST['delete_image'])): ?>active<?php endif; ?>"
                         id="images" role="tabpanel">


                        <?php if (isset($imgMessage)): ?>
                            <div class="bg-success text-white p-2 my-4">
                                <?= $imgMessage; ?>
                            </div>
                        <?php endif; ?>

                        <h5 class="mt-4"><?php if (isset($image)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?>
                            une
                            image :</h5>

                        <form action="event-form.php?event_id=<?= $event['id']; ?>&action=edit"
                              method="post"
                              enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="caption">Légende :</label>
                                <input class="form-control" type="text" placeholder="Légende" name="caption"
                                       id="caption"/>
                            </div>
                            <div class="form-group">
                                <label for="image">Fichier :</label>
                                <input class="form-control" type="file" name="images" id="image"/>
                            </div>

                            <input type="hidden" name="event_id" value="<?= $event['id']; ?>"/>

                            <div class="text-right">
                                <input class="btn btn-success" type="submit" name="add_image"
                                       value="Enregistrer"/>
                            </div>
                        </form>

                        <div class="row">
                            <h5 class="col-12 pb-4">Liste des images :</h5>
                            <?php foreach ($images as $image): ?>
                                <form action="event-form.php?event_id=<?= $event['id']; ?>&action=edit"
                                      method="post" class="col-4 my-3">
                                    <img src="../assets/img/events/<?= $image['name']; ?>" alt=""
                                         class="img-fluid"/>
                                    <p class="my-2"><?= $image['caption']; ?></p>
                                    <input type="hidden" name="img_id" value="<?= $image['id']; ?>"/>
                                    <div class="text-right"><input class="btn btn-danger" type="submit"
                                                                   name="delete_image" value="Supprimer"/>
                                    </div>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
</body>
</html>
