<?php

require ('models/legal-notices.php');
$legalNotices = getLegalN();
require('views/legal-notices.php');

