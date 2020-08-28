<?php
//Inicio la sesión
session_start();
require_once('conexion.inc');

//Utiliza los datos de sesion comprueba que el usuario este autenticado
if ($_SESSION["autenticado"] != "SI") {
     header("Location: ../index.php");
     exit(); //fin del scrip
}

if ((isset($_POST['idArchiCompar']))) {
     // echo $_POST['idArchiCompar'];
     try {
          $qry = sprintf("select usu.nombre from usuarios as usu inner join archicompartidos as ac on usu.id = ac.idUsuarioCompartido where ac.idArchivo = '%s'", $_POST['idArchiCompar']);

          if($res = mysqli_query($conex, $qry)){
               // $array = ['usuario' => []];
               
               while ($fila = mysqli_fetch_assoc($res)) {
                    // array_push($array['usuario'],[$row['usuario']]);
                    array_push($datos[] = $fila['nombre']);
               }
          }
     } catch (Exception $ex) {
          echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
     } finally {
          mysqli_close($conex);
          mysqli_free_result($res);
          unset($_POST['idArchiCompar']);
          unset($lst);
     }
     echo json_encode($datos);
};
