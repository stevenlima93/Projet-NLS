<?php

require_once 'tools/common.php';

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['service_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

	$query = $db->prepare('SELECT img FROM services WHERE id = ?');
	$query->execute(array($_GET['service_id']));
	$imageToDelete = $query->fetch();

	$query = $db->prepare('DELETE FROM services WHERE id = ?');
	$result = $query->execute(
		[
			$_GET['service_id']
		]
	);
	$path= '../assets/img/services/';
    unlink( $path . $imageToDelete['img']);

    //générer un message à afficher pour l'administrateur
	if($result){
		$message = "Suppression efféctuée.";
	}
	else{
		$message = "Impossible de supprimer la séléction.";
	}
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM services ORDER BY id DESC');
$services = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="FR">
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
					<header class="pb-4 d-flex justify-content-between">
						<h4>Liste des services</h4>
						<a class="btn btn-primary" href="service-form.php">Ajouter un service</a>
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
								<th>Adresse</th>
								<th>Horaires</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>

							<?php if($services): ?>
							<?php foreach($services as $service): ?>

							<tr>
								<!-- htmlentities sert à écrire les balises html sans les interpréter -->
								<th><?= htmlentities($service['id']); ?></th>
								<td><?= htmlentities($service['name']); ?></td>
								<td><?= htmlentities($service['address']); ?></td>
								<td><?= htmlentities($service['schedule']); ?></td>
								<td>
									<a href="service-form.php?service_id=<?= $service['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
									<a href="services-list.php?service_id=<?= $service['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
								</td>
							</tr>

							<?php endforeach; ?>
							<?php else: ?>
								Aucun service enregistré.
							<?php endif; ?>

						</tbody>
					</table>

				</section>

			</div>

		</div>
	</body>
</html>
