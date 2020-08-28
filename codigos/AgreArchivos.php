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
// $Accion_Formulario = $_SERVER['PHP_SELF']; DESCOMENTAR ESTO SI SE HABRE LA PAGINA APARTE

//verifica si se ha hecho clic en el boton guardar
if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmArchi") && isset($_POST['txtComentario'])) {

     /** 
      *! - Es el nombre original del archivo.
      *! - El tipo MIME del archivo,.. image/gif, application/pdf, application/msword,.. etc
      *!- La ubicación del archivo temporal que se crea cuando se sube un archivo al servidor. Es en esta variable de donde se leen los datos del archivo en sí. Si estos datos no son copiados o movidos a otro lugar, o en nuestro caso, almacenados en una base de datos, se pueden perder, ya que PHP elimina este archivo después de un determinado tiempo.
      *! - El tamaño del archivo en bytes.
      */
     $nomArchi = $_FILES['txtArchi']['name'];
     $tipoArchi = $_FILES['txtArchi']['type'];
     $archivo = $_FILES['txtArchi']['tmp_name'];
     $tamanho = $_FILES["txtArchi"]["size"];
     $idUsuario = $_SESSION['id'];
     $comentario = trim($_POST['txtComentario']);
     $estado = 1;

     if ($nomArchi != "") {
          $archivo = addslashes(file_get_contents($archivo));

          $qry = "insert into archivos (idUsuario, nomArchi, tipoArchi, archivo, tamanho, Estado, comentario, idCarpeta) values ('$idUsuario','$nomArchi','$tipoArchi','$archivo',$tamanho,$estado,'$comentario',1)";

          try {
               $result = mysqli_query($conex, $qry) or die(mysqli_error($conex));

               if ($result) {
                    header("location: ../mydrive.php");
                    $_SESSION['exito'] = 'ArchiGuardado';
               }

          } catch (Exception $e) {
               echo 'Excepción capturada: ', $e->getMessage(), "\n";
          } finally {
               unset($_FILES['txtArchi']);
               mysqli_close($result);
          }
     } else echo "<script> alert('No hay archivo para cargar'); </script>";
}
?>