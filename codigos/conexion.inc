<?php
    //parametros de conexion (opcionales)
	$servername   = "localhost";
    //$usuario   = getenv('DB_USER');
    //$contra    = getenv('DB_PASS');
    $database = "virtualbox";

    //suprime advertencias
    error_reporting(0);

    $conex = mysqli_connect($servername, 'root', '', $database);
	//proceso para conectar con el servidor
    //$conex = mysqli_connect($servername, 'root', '', $database);
    mysqli_set_charset($conex, 'utf8');
    
    if(!$conex){
        echo "<h3><font color='red'>Error: No se puede conectar al servidor de MySQL.</font></h3>" . "<hr>";
        echo "<strong>Número........:</strong> " . mysqli_connect_errno() . "<br>";
        echo "<strong>Descripción...:</strong> " . mysqli_connect_error() . "<br>";
        exit;
    }
?>