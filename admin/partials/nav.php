<?php
	//nombre d'enregistrements de la table user
	$nbUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
	//nombre d'enregistrements de la table category
	//nombre d'enregistrements de la table article
	$nbServices = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $nbCategories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $nbFaq = $db->query("SELECT COUNT(*) FROM faq")->fetchColumn();
    $nbBills = $db->query("SELECT COUNT(*) FROM bills")->fetchColumn();
    $nbLegalNotices = $db->query("SELECT COUNT(*) FROM legal_notices")->fetchColumn();
    $nbEvents = $db->query("SELECT COUNT(*) FROM events")->fetchColumn();
?>
<nav class="col-3 py-2 categories-nav">
	<a class="d-block btn btn-warning mb-4 mt-2" href="../index.php">Site</a>
	<ul>
		<li><a href="users-list.php">Gestion des utilisateurs (<?= $nbUsers; ?>)</a></li>
        <li><a href="bills-list.php">Gestion des factures (<?= $nbBills; ?>)</a></li>
		<li><a href="services-list.php">Gestion des services (<?= $nbServices; ?>)</a></li>
        <li><a href="events-list.php">Gestion des évènements (<?= $nbEvents; ?>)</a></li>
        <li><a href="faq-list.php">Gestion de la FAQ (<?= $nbFaq; ?>)</a></li>
		<li><a href="categories-list.php">Gestion des catégories (<?= $nbCategories; ?>)</a></li>
		<li><a href="legal-notices-list.php">Gestion des mentions légales (<?= $nbLegalNotices; ?>)</a></li>
	</ul>
</nav>
