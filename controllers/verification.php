<?php

require ('models/profil.php');
require ('models/verification.php');
if (isset($_POST['btnBad'])){
    verif($_POST['btnBad']);
}
$infoUser= getUser($_SESSION['user']['id']);
require('views/verification.php');