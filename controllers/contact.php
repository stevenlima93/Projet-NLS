<?php

require ('models/profil.php');
    if (isset($_POST['btnBad'])){
        verif($_POST['btnBad']);
    }

require('views/contact.php');