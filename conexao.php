<?php
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'maquinas';

    $conn = new mysqli($server, $user, $pass, $db);

    if($conn->connect_error){
        die("Falha ao conectar " . $conn->connect_error);
    } 
?> 