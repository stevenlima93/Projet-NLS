<?php

function getServices(){
    $db= dbConnect();
    $query=$db->query('SELECT * FROM services');
    return $query->fetchAll();
}