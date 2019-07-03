<?php

function connect($login){
    $db=dbConnect();
    if(isset($login)){
     if(empty($_POST['email']) ){
         return $errorLog = "Email obligatoire";
     }
     elseif(empty($_POST['password']) ){
         return $errorLog = "Mot de passe obligatoire";
     }
     if(empty($messages)){
         $query= $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
         $query->execute(array($_POST['email'], hash('md5', $_POST['password']) ) );
         $user=$query->fetch();
         if($user){
             $_SESSION['user']['admin']= $user['admin'];
             $_SESSION['user']['name']= $user['name'];
             $_SESSION['user']['id']= $user['id'];
         }
         else{
             return $errorLog= "Mauvais identifiants";
         }
     }
    }
}