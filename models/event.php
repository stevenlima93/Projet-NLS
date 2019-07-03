<?php

function getEvents()
{
    $db = dbConnect();
    if (isset($_POST['datePiker'])) {
        $events = $db->prepare('SELECT * FROM events WHERE is_published=1 AND created_at=?');
        $events->execute(array($_POST['datePiker']));
        return $events->fetchAll();
    } else {
        $events = $db->query('SELECT * FROM events WHERE is_published=1');
        return $events->fetchAll();
    }
}

function getEvent()
{
    $db = dbConnect();
    $event = $db->prepare('SELECT * FROM events WHERE id=? AND is_published=1');
    $event->execute(array($_GET['event_id']));
    return $event->fetch();

}

function limitEvent()
{
    $db = dbConnect();
    $events = $db->query('SELECT * FROM events WHERE is_published=1 ORDER BY created_at DESC LIMIT 3');
    return $events->fetchAll();
}

function getImages($eventId){
    $db = dbConnect();

    $queryImages = $db->prepare('SELECT name FROM images WHERE event_id = ?');
    $queryImages->execute(array($eventId));
    return $queryImages->fetchAll();
}