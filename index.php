<?php

require_once 'tools/common.php';
if(isset($_GET['logout']) AND isset($_SESSION['user'])){
    unset($_SESSION['user']);
}
$db= dbConnect();

if(isset($_GET["page"])){
    switch ($_GET["page"]){
        case "services":
            require_once 'controllers/services.php';
        break;

        case "mentions-legales":
            require_once 'controllers/legal-notices.php';
            break;

        case "connexion":
            require_once 'controllers/connexion.php';
            break;

        case "verification":
            require_once 'controllers/verification.php';
            break;

        case "my-account":
            require_once 'controllers/my-account.php';
            break;

        case "contact":
            require_once 'controllers/contact.php';
            break;

        case "events-list":
            require_once 'controllers/events.php';
            break;

        case "event":
            require_once 'controllers/event.php';
            break;

        case "faq":
            require_once 'controllers/faq.php';
            break;

        default:
            header("location:index.php");
            exit;
            break;
    }
}
else{
    require('controllers/index.php');
}