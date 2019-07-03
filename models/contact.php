<?php

function verif($btnSend){
    $db= dbConnect();
    if(isset($btnSend)){
        $verifUpdate= $db->prepare('UPDATE users SET verification=? WHERE id=?');
        $verifUpdate->execute(array($btnSend, $_SESSION['user']['id']));

        if($verifUpdate){
            $_SESSION['user']['verification'];
            header("location:contact.php");
            exit;
        }
    }
}