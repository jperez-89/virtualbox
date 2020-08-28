<?php
require_once('codigos/conexion.inc');
if (isset($_POST['txtUsua']) && isset($_POST['txtContra']) && isset($_POST['txtNomb']) && isset($_POST['txtEmail'])) {
	//Crea la instrucción para registrar el usuario
	$AuxSql = sprintf(
		"insert into usuarios(usuario,contra,nombre,email) values('%s',md5('%s'),'%s','%s')",
		trim($_POST['txtUsua']),
		trim($_POST['txtContra']),
		trim($_POST['txtNomb']),
		trim($_POST['txtEmail'])
	);
	try {
		//Ejecutamos la sentencia
		$Regis = mysqli_query($conex, $AuxSql, MYSQLI_STORE_RESULT);

		//Liberamos la memoria de la variable
		mysqli_free_result($Regis);
		//Iniciamos la sesion
		session_start();
		$_SESSION["autenticado"] = "SI";
		$_SESSION["nombre"] = trim($_POST['txtNomb']);
		$_SESSION["usuario"] = trim($_POST['txtUsua']);

		//Obtenemos el id del usuario registrado para poder crear la carpeta
		$query = sprintf("select id from usuarios where usuario = '%s'", trim($_POST['txtUsua']));
		$result = mysqli_query($conex, $query, MYSQLI_STORE_RESULT);
		$row = mysqli_fetch_assoc($result);
		mysqli_free_result($result);

		//Creamos la carpeta raiz del usuario
		$AuxSql = sprintf("insert into carpetas(nomCarpeta, idUsuario) values('%s','%s')", trim('Raiz'), trim($row['id']));
		$result = mysqli_query($conex, $AuxSql, MYSQLI_STORE_RESULT);
		mysqli_free_result($result);

		header("location: index.php");
		exit();
	} catch (Exception $e) {
		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
	} finally {
		unset($_POST['txtUsua']);
		unset($_POST['txtContra']);
		unset($_POST['txtNomb']);
		unset($_POST['txtEmail']);
		mysqli_close($conex);
	}
} //fin del if principal
?>
<!doctype html>
<html>

<head>
	<?php include_once('partes/encabe.inc'); ?>
	<title>Registrarse al Sitio</title>
</head>

<body class="container-fluid">
	<div class="contenedorRegistro">

		<div class="Registro">

			<div class="card">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="card-header">
						<strong>Datos Generales</strong>
					</div>
					<div class="card-body">
						<fieldset>
							<label>Usuario:</label>
							<input type="text" name="txtUsua" size="22" maxlength="15" required /><br>
							<label>Contrase&ntilde;a:</label>
							<input type="password" name="txtContra" size="22" maxlength="15" required /><br>
							<label>Nombre Completo:</label>
							<input type="text" name="txtNomb" size="40" maxlength="30" required /><br>
							<label>Correo Electrónico:</label>
							<input type="text" name="txtEmail" size="40" maxlength="50" required /><br>
						</fieldset>
					</div>
					<div class="card-footer">
						<input class="btn btn-outline-success btn-sm" type="submit" value="Aceptar" />
					</div>
				</form>
			</div>
		</div>

		<div class="imagenRegistro">
			<img src="imagenes/register.svg" />
		</div>

	</div>
	<?php include_once('partes/final.inc'); ?>
</body>

</html>