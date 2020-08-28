<?php
	//Inicio la sesión
	session_start();
	require_once('conexion.inc');
	
	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: ../index.php");
		exit();
	}

	if (!empty($_GET['id'])) {
		$result = $conex->query("SELECT nomArchi, archivo, tipoArchi FROM archivos WHERE id = {$_GET['id']}");

		if ($result->num_rows > 0) {
			$imgDatos = mysqli_fetch_assoc($result);

			//Mostrar archivo
			// Este header lo que hace es descargarlo con el nombre original
			// header("Content-Disposition: attachment; filename=".$imgDatos['nomArchi']);

			header("Content-type:".$imgDatos['tipoArchi']);
			echo $imgDatos['archivo'];
		} else {
			echo 'Imagen no existe...';
		}
	}
