<?php
//Inicio la sesión

session_start();
require_once('conexion.inc');

//Utiliza los datos de sesion comprueba que el usuario este autenticado
if ($_SESSION["autenticado"] != "SI") {
	header("Location: ../index.php");
	exit(); //fin del scrip
}

 $respuesta = false;

if (!empty($_POST['id'])) {
     //Extraer imagen de la BD mediante GET
     try {
          $query = "update carpetas set Estado = 0 where id = {$_POST['id']};";
          $result = mysqli_query($conex, $query);
          $respuesta = true;
          echo $respuesta;
     } catch (Exception $e) {
          echo 'Excepción capturada: ', $e->getMessage(), "\n";
     } finally {
          mysqli_close($conex);
     }
     
     if ($respuesta) {
          header("Location: ../mydrive.php");
          // $_SESSION['exito'] = 'CarpetaBorrada';
          // echo $respuesta;
     } 
     else {
          echo "No se logró borrar la carpeta";
          // $_SESSION['exito'] = 'CarpetaNoBorrada';
          // echo $respuesta; 
     }
}
