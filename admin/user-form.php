<?php

require_once 'tools/common.php';

$messages = [];

$name = null;
$firstname = null;
$address = null;
$birthdate = null;
$email = null;
$number = null;


if (isset($_POST['save'])) {

    if (empty($_POST['name'])) {
        $messages['name'] = 'Le prénom de l\'utilisateur est obligatoire';
    }

    if (empty($_POST['firstname'])) {
        $messages['firstname'] = 'Le nom de l\'utilisateur est obligatoire';
    }
    if (empty($_POST['address'])) {
        $messages['address'] = 'L\'adresse est obligatoire';
    }
    if (empty($_POST['email'])) {
        $messages['email'] = 'L\'adresse mail est obligatoire';
    }

    if (empty($messages)) {

        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

        for ($i = 0; $i < 12; $i++) {
            $password .= ($i % 2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }

        $query = $db->prepare('INSERT INTO users (name, firstname, birthdate, number, address, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $newUser = $query->execute(
            [
                htmlspecialchars($_POST['name']),
                htmlspecialchars($_POST['firstname']),
                htmlspecialchars($_POST['birthdate']),
                htmlspecialchars($_POST['number']),
                htmlspecialchars($_POST['address']),
                htmlspecialchars($_POST['email']),
                htmlspecialchars($password)
            ]
        );

        if ($newUser) {
            $to = $_POST['email'];
            $subject = "Votre espace personel";
            $msg = 'Voici le mot de passe pour accéder à votre' . $password;
            $headers = "From : Mairie de Noisy le sec";
            mail($to, $subject, $msg, $headers);

            $_SESSION['message'] = 'Nouvel utilisateur ajoutée';
            header("location:users-list.php");
            exit;
        } else {
            $_SESSION['message'] = 'Echec de l\'ajout';
        }
    } else {
        $name = $_POST['name'];
        $firstname = $_POST['firstname'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $number = $_POST['number'];
        $birthdate = $_POST['birthdate'];
    }
}
if (isset($_POST['update'])) {

    if (empty($_POST['name'])) {
        $messages['name'] = 'Le nom de l\'utilisateur est obligatoire';
    }

    if (empty($_POST['firstname'])) {
        $messages['firstname'] = 'Le prénom de l\'utilisateur est obligatoire';
    }

    if (empty($_POST['address'])) {
        $messages['address'] = 'L\'adresse est obligatoire';
    }

    if (empty($_POST['email'])) {
        $messages['email'] = 'L\'adresse mail est obligatoire';
    }

    if (empty($messages)) {

        $query = $db->prepare('UPDATE users SET
		name = :name,
		firstname = :firstname,
		birthdate = :birthdate,
		address = :address,
		email = :email,
		admin = :admin,
		number = :number
		WHERE id = :id'
        );

        $resultUser = $query->execute([
            'name' => htmlspecialchars($_POST['name']),
            'firstname' => htmlspecialchars($_POST['firstname']),
            'birthdate' => htmlspecialchars($_POST['birthdate']),
            'address' => htmlspecialchars($_POST['address']),
            'email' => htmlspecialchars($_POST['email']),
            'admin' => htmlspecialchars($_POST['admin']),
            'number' => htmlspecialchars($_POST['number']),
            'id' => $_POST['id']
        ]);
        if ($resultUser) {
            $_SESSION['message'] = "Mise à jour réussie";
            header('location:users-list.php');
            exit;
        } else {
            $_SESSION['message'] = "Echec de la mise à jour";
        }
    }

}
if (isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM users WHERE id = ?');
    $query->execute(array($_GET['user_id']));
    $getUser = $query->fetch();
}

if (isset($_GET['user_id']) AND $_GET['user_id'] !== $getUser['id']) {
    header('location:users-list.php');
    exit;
}

?>

<!DOCTYPE HTML>
<html lang="fr">
<head>

    <title>Administration des utlilisateurs</title>

    <?php require 'partials/head_assets.php'; ?>

</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-3">
                <h4><?php if (isset($getUser)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un utilisateur</h4>
            </header>

            <div class="tab-content">

                <?php if (isset($message)): ?>
                    <div class="bg-danger text-white">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form action="user-form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['name']) : $name; ?>" type="text"
                               placeholder="Nom" name="name" id="name"/>
                        <span class="text-danger"><?= isset($messages['name']) ? $messages['name'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Prénom :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['firstname']) : $firstname; ?>"
                               type="text" placeholder="Prénom" name="firstname" id="firstname"/>
                        <span class="text-danger"><?= isset($messages['firstname']) ? $messages['firstname'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Date de naissance :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['birthdate']) : $birthdate; ?>"
                               type="date" name="birthdate" id="birthdate"/>
                        <span class="text-danger"><?= isset($messages['birthdate']) ? $messages['birthdate'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresse postal :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['address']) : $address; ?>"
                               type="text" name="address" id="address"/>
                        <span class="text-danger"><?= isset($messages['address']) ? $messages['address'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="number">Mobile :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['number']) : $number; ?>"
                               type="number" name="number" id="number"/>
                        <span class="text-danger"><?= isset($messages['number']) ? $messages['number'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse mail :</label>
                        <input class="form-control"
                               value="<?= isset($getUser) ? htmlentities($getUser['email']) : $email; ?>" type="email"
                               name="email" id="email"/>
                        <span class="text-danger"><?= isset($messages['email']) ? $messages['email'] : ""; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="admin"> Administrateur ?</label>
                        <select class="form-control" name="admin" id="admin">
                            <option value="0"
                                    <?php if (isset($getUser) && $getUser['admin'] == 0): ?>selected<?php endif; ?>>Non
                            </option>
                            <option value="1"
                                    <?php if (isset($getUser) && $getUser['admin'] == 1): ?>selected<?php endif; ?>>Oui
                            </option>
                        </select>
                    </div>

                    <div class="text-right">

                        <?php if (isset($getUser)): ?>
                            <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>
                        <?php else: ?>
                            <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                        <?php endif; ?>
                    </div>


                    <?php if (isset($getUser)): ?>
                        <input type="hidden" name="id" value="<?= $getUser['id']; ?>"/>
                    <?php endif; ?>

                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>