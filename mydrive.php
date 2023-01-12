<?php
//Inicio la sesión
session_start();
require_once('codigos/conexion.inc');
//Utiliza los datos de sesion comprueba que el usuario este autenticado
if ($_SESSION["autenticado"] != "SI") {
	header("Location: index.php");
	exit(); //fin del scrip
}
?>

<!doctype html>
<html>

<head>
	<?php include_once('partes/encabe.inc'); ?>
	<title>Virtual Box</title>
</head>

<body class="container-fluid">
	<main>
		<header>
			<!-- BREADCRUMB -->
			<nav aria-label="breadcrumb">
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="mydrive.php">Mi Unidad</a></li>
							<li id="bc" class="breadcrumb-item active hide" aria-current="page"></li>
							<ul>
					</div>
				</div>
			</nav>
		</header>

		<!-- CARD AGREGAR - SALIR -->
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-md-6 dropdown">
						<button type="button" class="btn btn-sm btn-success dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Agregar
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" data-target="#ModalArchivo" data-toggle="modal" href="">Agregar Archivos</a>
							<a class="dropdown-item" data-target="#ModalCarpeta" data-toggle="modal" href="">Agregar Carpeta</a>
						</div>
					</div>
					<div class="col-md-6 Salir">
						<a class="btn btn-outline-danger btn-sm " href="codigos/salir.php">Salir - <?php echo $_SESSION['nombre'] ?> </a>
					</div>
				</div>
			</div>
		</div><br>

		<!-- TABLA MIS CARPETAS -->
				<?php
				$conta = 0;
				$qry1 = sprintf("select * from carpetas where estado > 0 and idUsuario = '%s' and nomCarpeta != '%s'", $_SESSION['id'], 'Raiz');
				$res1 = mysqli_query($conex, $qry1);
				if(mysqli_num_rows($res1) > 0){
					echo '';
					echo '<div class="card mb-3">
								<div class="card-header">
								<h5>Mis Carpetas</h5>
							</div>';
					echo '<div class="card-body">';
					echo '<table id="Tabla_Carpetas" class="table">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>Nombre</th>';
					echo '<th>Compartido con</th>';
					echo '<th>Fecha Creación</th>';
					echo '<th>Compartir</th>';
					echo '<th>Accion</th>';
					echo '</tr>';
					echo '</thead>';

					echo '<tbody>';
					while ($fila = mysqli_fetch_array($res1)) {
						$qry = sprintf("select nombre from usuarios where id = '%s'", $fila['compartidaCon']);
						$res = mysqli_query($conex, $qry);
						$compartido = mysqli_fetch_array($res);

						$conta++;
						echo "<tr>";
						echo '<td><button onClick=MostrarArchiCarpeta('.$fila['id'].',"'.$fila['nomCarpeta'].'") class="btn btn-outline-secondary">' . $fila['nomCarpeta'] . '</button></td>';
						echo "<td>$compartido[nombre]</td>";
						echo "<td>$fila[fchCreacion] </td>";
						echo "<td><a name='id' value='$fila[id]' class='btn btn-info btn-sm' href=codigos/comparCarpeta.php?id=$fila[id]>Compartir</a></td>";
						echo "<td><a class='btn btn-danger btn-sm' onClick=EliminarCarpeta($fila[id])>Eliminar</a></td>";
						echo "</tr>";
					}
					echo '</tbody>';
					echo "</table>";
					echo "</div>";
					echo "</div>";
				} 
				mysqli_free_result($res1);
				?>

		<!-- TABLA MIS ARCHIVOS -->
		<div id="RenderTablaArchivos">
			<div class="card mb-3">
				<div class="card-header">
					<h5>Mis Archivos</h5>
				</div>
				<div class="card-body">
					<?php
					$conta = 0;
					$qry2 = sprintf("select * from archivos where idUsuario = '%s' and Estado = '%s' and idCarpeta = '%s'", $_SESSION['id'], 1, 1);
					$res2 = mysqli_query($conex, $qry2);

					if (mysqli_num_rows($res2) > 0) {
						echo '<table id="Tabla_Archivos" class="table">';
						echo '<thead>';
						echo '<tr>';
						echo '<th>Nombre</th>';
						echo '<th>Comentario</th>';
						echo '<th>Tipo</th>';
						echo '<th>Tama&ntilde;o (bytes)</th>';
						echo '<th>Fecha Almacenado</th>';
						echo '<th>Compartir</th>';
						echo '<th>Accion</th>';
						echo '<th>Compartido</th>';
						echo '</tr>';
						echo '</thead>';

						echo '<tbody>';
						while ($fila = mysqli_fetch_assoc($res2)) {
							$compar = 'No';
							$qry3 = sprintf("select idArchivo from archicompartidos where idArchivo = '%s'", $fila['id']);
							$res3 = mysqli_query($conex, $qry3);
							$compartido = mysqli_fetch_assoc($res3);
							$conta++;
							echo "<tr>";
							echo "<td><a class='text-lowercase' target='blank' href=codigos/AbriArchivos.php?id=$fila[id]>$fila[nomArchi]</a></td>";
							echo "<td>$fila[comentario]</td>";
							echo "<td>$fila[tipoArchi]</td>";
							echo "<td>$fila[tamanho] </td>";
							echo "<td>$fila[fchRegistro] </td>";
							echo "<td><button onClick='MostrarModalCompartirArchi($fila[id])' class='btn btn-info btn-sm' href=''>Compartir</button></td>";
							echo "<td><a onClick=EliminarArchi($fila[id]) class='btn btn-danger btn-sm' href='#'>Eliminar</a></td>";
							if ($compartido['idArchivo'] == $fila['id']) {
								echo "<td class='text-center'><a onClick=ArchivoCompartidoCon($fila[id]) class='text-decoration-none' href='#'>Sí - Ver</a></td>";
							} else {
								echo "<td class='text-center'>$compar</td>";
							}
							echo "</tr>";
						}
						echo '</tbody>';
						echo "</table>";
					}

					mysqli_free_result($res2,$res3);
					if ($conta == 0) echo '<h6 class="text-muted">Sin archivos</h6>';
					if ($_SESSION['exito'] == 'ArchiGuardado') {
						echo "<script> MostrarSweetAlert('success','Archivo Guardado'); </script>";
					} elseif ($_SESSION['exito'] == 'ArchiCompartido') {
						echo "<script> MostrarSweetAlert('success', 'Archivo Compartido'); </script>";
					} elseif ($_SESSION['exito'] == 'ArchiBorrado') {
						echo "<script> MostrarSweetAlert('success','Archivo Borrado'); </script>";
					} elseif ($_SESSION['exito'] == 'noExisteCorreo') {
						echo "<script> MostrarSweetAlert('error','El correo no existe'); </script>";
					}
					unset($_SESSION['exito']);
					?>

				</div>
			</div>
		</div>

		<!-- TABLA ARCHIVOS CARPETA -->
		<div id="RenderTablaArchiCarpetas" class="hide">
			<div class="card mb-3">
				<div class="card-header">
					<h5 id="NomCarpeta"></h5>
				</div>
				<div class="card-body">
					<?php
					$conta = 0;
					$qry2 = sprintf("select * from archivos 
					inner join carpetas on carpetas.id = archivos.idCarpeta
					where carpetas.idUsuario = '%s' and carpetas.id = '%s'", $_SESSION['id'], 8);
					$res2 = mysqli_query($conex, $qry2);
					if(mysqli_num_rows($res2)> 0){
						echo '<table id="Tabla_Archivos" class="table">';
						echo '<thead>';
						echo '<tr>';
						echo '<th>Nombre</th>';
						echo '<th>Comentario</th>';
						echo '<th>Tipo</th>';
						echo '<th>Tama&ntilde;o (bytes)</th>';
						echo '<th>Fecha Almacenado</th>';
						echo '<th>Compartir</th>';
						echo '<th>Accion</th>';
						echo '<th>Compartido con</th>';
						echo '</tr>';
						echo '</thead>';

						echo '<tbody>';
						while ($fila = mysqli_fetch_assoc($res2)) {
							$qry3 = sprintf("select nombre from usuarios where id = '%s'", $fila['compartidoCon']);
							$res3 = mysqli_query($conex, $qry3);
							$compartido = mysqli_fetch_assoc($res3);

							$conta++;
							echo "<tr>";
							echo "<td><a class='text-lowercase' target='blank' href=codigos/AbriArchivos.php?id=$fila[id]>$fila[nomArchi]</a></td>";
							echo "<td>$fila[comentario]</td>";
							echo "<td>$fila[tipoArchi]</td>";
							echo "<td>$fila[tamanho] </td>";
							echo "<td>$fila[fchRegistro] </td>";
							echo "<td><button onClick='MostrarModalCompartirArchi($fila[id])' class='btn btn-info btn-sm' href=''>Compartir</button></td>";
							echo "<td><a class='btn btn-danger btn-sm' href=codigos/borraArchi.php?id=$fila[id]>Eliminar</a></td>";
							echo "<td>$compartido[nombre]</td>";
							echo "</tr>";
						}
						echo '</tbody>';
						echo "</table>";
						if ($conta == 0) echo '<h6>La carpeta se encuetra vac&iacute;a </h6>';
					}else{
						echo '<h6 class="text-muted">La carpeta se encuetra vac&iacute;a </h6>';
					}

					
					mysqli_free_result($res2, $res3);
					// mysqli_close($conex);
					
					if ($_SESSION['exito'] == 'si') {
						echo "<script>
                 				MostrarAlerta('success', 'Has compartido el archivo');
						 </script>";
						unset($_SESSION['exito']);
					}
					?>
				</div>
			</div>
		</div>

		<!-- TABLA COMPARTIDO CONMIGO -->
		<?php
		// Sustrae los archivos compartidos conmigo
		$qry2 = sprintf("select * from archivos where compartidoCon = '%s'", $_SESSION['id'], 1);
		$res2 = mysqli_query($conex, $qry2);

		// Si devulve lineas muestra la tabla
		if (mysqli_num_rows($res2) > 0) {
		?>
			<div class="card">
				<div class="card-header">
					<h5>Compartido Conmigo</h5>
				</div>
				<div class="card-body">
					<?php
					$conta = 0;
					// $qry2 = sprintf("select * from archivos where compartidoCon = '%s'", $_SESSION['id'], 1);
					// $res2 = mysqli_query($conex, $qry2);

					echo '<table id="Tabla_Archivos" class="table">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>Propietario</th>';
					echo '<th>Nombre</th>';
					echo '<th>Comentario</th>';
					echo '<th>Tipo</th>';
					echo '<th>Tama&ntilde;o (bytes)</th>';
					echo '<th>Fecha Almacenado</th>';
					// echo '<th>Compartir</th>';
					// echo '<th>Accion</th>';
					echo '</tr>';
					echo '</thead>';

					echo '<tbody>';
					while ($fila = mysqli_fetch_assoc($res2)) {
						$qry3 = sprintf("select nombre from usuarios where id = '%s'", $fila['idUsuario']);
						$res3 = mysqli_query($conex, $qry3);
						$compartido = mysqli_fetch_assoc($res3);

						$conta++;
						echo "<tr>";
						echo "<td>$compartido[nombre]</td>";
						echo "<td><a class='text-lowercase' target='blank' href=codigos/AbriArchivos.php?id=$fila[id]>$fila[nomArchi]</a></td>";
						echo "<td>$fila[comentario]</td>";
						echo "<td>$fila[tipoArchi]</td>";
						echo "<td>$fila[tamanho] </td>";
						echo "<td>$fila[fchRegistro] </td>";
						// echo "<td><a class='btn btn-danger btn-sm' href=codigos/borraArchi.php?id=$fila[id]>Eliminar</a></td>";
						echo "</tr>";
					}
					echo '</tbody>';
					echo "</table>";
					mysqli_free_result($res2, $res3);
					mysqli_close($conex);
					if ($conta == 0) echo '<h6>La carpeta del usuario se encuetra vac&iacute;a </h6>';
					?>
				</div>
			</div>
		<?php } ?>
	</main>

	<div class="footer">
		<div class="row">
			<div class="col-md-12">
				<br>
			</div>
		</div>
	</div>

	<!-- MODAL SUBIR ARCHIVO -->
	<div class="modal fade" id="ModalArchivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agregar Archivos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body mt-2">
					<form action="codigos/AgreArchivos.php" method="post" enctype="multipart/form-data" name="frmArchi">
						<fieldset>
							<div class="input-group mb-3">
								<div class="custom-file">
									<input name="txtArchi" class="custom-file-input" type="file" id="txtArchi" size="60" required />
									<label class="custom-file-label" for="txtArchi">Buscar archivo...</label>
								</div>
							</div>
							<div class="input-group">
								<input name="txtComentario" class="form-control" type="text" id="txtComentario" size="50" placeholder="Comentario..." required />
							</div>
						</fieldset>
						<input type="hidden" name="OC_Aceptar" value="frmArchi" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input class="btn btn-primary" type="submit" name="Submit" value="Cargar" />
				</div>
				</form>
			</div>
		</div>
	</div>

	<!-- MODAL COMPARTIR ARCHIVO -->
	<div class="modal fade" id="ModalCompartir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Compartiendo Archivo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="codigos/CompArchivos.php" method="POST" name="frmCompartir">
						<fieldset>
							<div class="form-group">
								<input placeholder="Digite email..." type="email" class="form-control" name="txtEmail" required>
							</div>
							<input id="idArchiCompartido" type="hidden" name="idArchiCompartido" value="" />
						</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input class="btn btn-primary" type="submit" name="Submit" value="Compartir" />
				</div>
				</form>
			</div>
		</div>
	</div>

	<!-- MODAL MOSTRAR A QUIEN SE COMPARTIO LOS ARCHIVOS -->
	<div id="ModalListaArchiComparUsuarios" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Archivo Compartido con</h5>
					<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<div id="lstUsuarios"></div>
				</div>
				<div class="modal-footer">
					<button onclick="BorrarDivModalCompartido()" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL CREAR CARPETA -->
	<div class="modal fade" id="ModalCarpeta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agregar Carpeta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="codigos/AgreCarpeta.php" method="POST" name="frmCarpeta">
						<fieldset>
							<div class="form-group">
								<!-- <label for="recipient-name" class="col-form-label">Nombre:</label> -->
								<input type="text" name="txtNomCarpeta" class="form-control" id="txtNomCarpeta" placeholder="Nombre de la carpeta..." required />
							</div>
						</fieldset>
						<input type="hidden" name="OC_Aceptar" value="frmCarpeta" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input class="btn btn-primary" type="submit" name="Submit" value="Guardar" />
				</div>
				</form>
			</div>
		</div>
	</div>

	<?php include_once('partes/final.inc'); ?>
</body>

</html>
