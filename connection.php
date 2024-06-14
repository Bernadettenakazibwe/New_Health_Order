<?php

    $database= new mysqli("db","user","userpassword","edoc");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>