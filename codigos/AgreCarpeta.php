<?php
//Inicio la sesiÃ³n
session_start();

//Utiliza los datos de sesion comprueba que el usuario este autenticado
if ($_SESSION["autenticado"] != "SI") {
     header("Location: ../index.php");
     exit(); //fin del scrip
}

//conexion con mysql
require_once('conexion.inc');

//verifica si se ha hecho clic en el boton guardar
if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmCarpeta")) {
     $nomCarpeta = trim($_POST['txtNomCarpeta']);
     $idUsuario = $_SESSION['id'];

     $qry = "insert into carpetas (nomCarpeta, idUsuario) values ('$nomCarpeta','$idUsuario')";

     try {
          $result = mysqli_query($conex, $qry) or die(mysqli_error($conex));

          if ($result) {
               header("location: ../mydrive.php");
          }
     } catch (Exception $e) {
          echo 'Excepción capturada: ',  $e->getMessage(), "\n";
     } finally {
          unset($_POST['txtNomCarpeta']);
          mysqli_free_result($result);
          mysqli_close($conex);
     }
}
?>