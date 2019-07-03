<?php

function getUser($user){
    $db= dbConnect();
    $queryUser=$db->prepare('SELECT * FROM users WHERE id=?');
    $queryUser->execute(array($user));
    return $queryUser->fetch();
}