<?php
header("Access-Control-Allow-Origin: *");
try{
    $db = new PDO('mysql:host=localhost;dbname=projet-nls;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $exception)
{
    die( 'Erreur : ' . $exception->getMessage() );
}
# Get JSON as a string
$data = file_get_contents('php://input');


    $res = new stdClass();
    $inputDate = $_POST['date'];
    $array = [];

    $queryString = 'SELECT * FROM events WHERE is_published = 1';

    if ($inputDate) {
        $queryString .= ' AND created_at = \'' . $inputDate . '\'';
    }

        $queryString .= ' ORDER BY created_at DESC';

    $query = $db->query($queryString);
    $events = $query->fetchAll();

    for ($i = 0; $i < sizeof($events); $i++) {
        $array[$i] = [
            "title" => $events[$i]['title'],
            "created_at" => $events[$i]['created_at'],
            "summary" => $events[$i]['summary'],
            "id" => $events[$i]['id'],
            "image" => $events[$i]['image'],
        ];
    }
    $res->inputDate = $array;
    if (empty($events)) {
        $res->msg = "Aucun événement ne correspond a cette date !";
    }
    echo json_encode($res);