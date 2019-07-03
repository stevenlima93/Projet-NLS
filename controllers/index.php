<?php

require ('models/event.php');
$events = limitEvent();
require ('views/index.php');
