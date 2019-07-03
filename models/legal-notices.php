<?php

function getLegalN(){
    $db= dbConnect();
    $query=$db->query('SELECT * FROM legal_notices');
    return $query->fetchAll();
}