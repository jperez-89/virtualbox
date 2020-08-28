<?php
//Inicio la sesión
session_start();
require_once('conexion.inc');

//Utiliza los datos de sesion comprueba que el usuario este autenticado
if ($_SESSION["autenticado"] != "SI") {
	header("Location: ../index.php");
	exit(); //fin del scrip
}

if ((isset($_POST['idArchiCompartido']))) { 
	try{
		$qry = sprintf("select id from usuarios where email = '%s'", trim($_POST['txtEmail']));
		$res = mysqli_query($conex, $qry);
		$compartido = mysqli_fetch_assoc($res);

		if ($compartido['id'] != '') {
			// $qry = sprintf("update archivos set compartidoCon = '%s' where id = '%s'", $compartido['id'], $_POST['CampoId']);

			$qry = sprintf("insert into archicompartidos (idArchivo, idUsuarioPropietario, idUsuarioCompartido) values (".$_POST['idArchiCompartido'].",".$_SESSION['id'].",".$compartido['id'].")");

			$res = mysqli_query($conex, $qry);

			if ($res) {
				header("Location: ../mydrive.php");
				$_SESSION['exito'] = 'ArchiCompartido';
			}
		} else {
			header("Location: ../mydrive.php");
			$_SESSION['exito'] = 'noExisteCorreo';

		}
	}catch(Exception $ex){
		echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
	}finally{
		mysqli_close($conex);
		mysqli_free_result($res);
		unset($_POST['idArchiCompartido']);
		unset($_POST['txtEmail']);
	}
}