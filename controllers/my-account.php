<?php

require ('models/profil.php');
require ('models/my-account.php');

$infoUser= getUser($_SESSION['user']['id']);
$bills= getBills();
require('views/my-account.php');