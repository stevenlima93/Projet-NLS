<?php


function getBills(){

    $db= dbConnect();
    $queryBills= $db->prepare('SELECT * FROM bills WHERE user_id=?');
    $queryBills->execute(array($_SESSION['user']['id']));
    return $queryBills->fetchAll();

}