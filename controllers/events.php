<?php
//model contient le php
require('models/event.php');
$events = getEvents();
if (isset($_POST['allEvents'])) {
    header('location:index.php?page=events-list');
    exit;
}
//contient le html
require('views/events-list.php');