<?php


setlocale(LC_ALL, "fr_FR");

function dbConnect(){
  try{
  	return $db = new PDO('mysql:host=localhost;dbname=projet-nls;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  }
  catch (Exception $exception)
  {
  	die( 'Erreur : ' . $exception->getMessage() );
  }
}

session_start();

