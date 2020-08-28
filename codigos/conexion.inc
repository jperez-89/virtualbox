<?php
    $server = "us-cdbr-east-02.cleardb.com";
    $username = "b480d8977a2502";
    $password = "485cd840";
    $db = "heroku_bede1c0d72a2c3e";

    //suprime advertencias
    error_reporting(0);

    $conex = mysqli_connect($server, $username, $password, $db);
    mysqli_set_charset($conex, 'utf8');

    if(!$conex){
        echo "<h3><font color='red'>Error: No se puede conectar al servidor de MySQL.</font></h3>" . "<hr>";
        echo "<strong>Número........:</strong> " . mysqli_connect_errno() . "<br>";
        echo "<strong>Descripción...:</strong> " . mysqli_connect_error() . "<br>";
        exit;
    }
?>