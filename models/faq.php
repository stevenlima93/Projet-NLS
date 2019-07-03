<?php

function getFaq(){
    $db= dbConnect();
    $queryFaq=$db->query('SELECT * FROM faq');
    return $queryFaq->fetchAll();
}

function getCategories(){
    $db= dbConnect();
    $queryCat=$db->query('SELECT DISTINCT c.id, c.name as nameCat FROM categories c JOIN faq f ON c.id=f.category_id');
    return $queryCat->fetchAll();
}