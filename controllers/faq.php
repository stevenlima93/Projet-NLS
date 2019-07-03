<?php

require('models/faq.php');
$categories= getCategories();
$faq= getFaq();
require('views/faq.php');

