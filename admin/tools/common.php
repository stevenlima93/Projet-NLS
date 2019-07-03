<?php


setlocale(LC_ALL, "fr_FR");

try {
    $db = new PDO('mysql:host=localhost;dbname=projet-nls;charset=utf8', 'root', 'root', 'stevenlicbs', 'Steven93', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $exception) {
    die('Erreur : ' . $exception->getMessage());
}

//ouverture de session pour connexions utilisateurs et admins
session_start();

if (!isset($_SESSION['user']) OR isset($_SESSION['user']) AND $_SESSION['user']['admin'] == 0) {
    header("location:../index.php");
    exit;
}