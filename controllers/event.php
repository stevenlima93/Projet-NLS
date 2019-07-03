<?php

require_once 'models/event.php';
$event= getEvent();
$images = getImages($_GET['event_id']);
require_once 'views/event.php';
