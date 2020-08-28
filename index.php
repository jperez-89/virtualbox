<?php
require_once('codigos/conexion.inc');
$Accion_Formulario = $_SERVER['PHP_SELF'];
if ((isset($_POST['txtUsua'])) && (isset($_POST['txtContra']))) {

    $auxSql = sprintf("select id, nombre, usuario from usuarios Where usuario = '%s' and contra = md5('%s')", $_POST['txtUsua'], $_POST['txtContra']);
    $regis = mysqli_query($conex, $auxSql);
    mysqli_close($conex);

    //libera los inputs del cache
    unset($_POST['txtUsua']);
    unset($_POST['txtContra']);

    if (mysqli_num_rows($regis) > 0) {
        $tupla = mysqli_fetch_assoc($regis);

        //usuario y clave correctos, se define una sesion y datos de interes
        session_start();
        $_SESSION["autenticado"] = "SI";
        $_SESSION["nombre"] = $tupla['nombre'];
        $_SESSION["usuario"] = $tupla['usuario'];
        $_SESSION["id"] = $tupla['id'];

        header("location: mydrive.php");
    } else {
        header("location: errores/400.php");
        exit();
    }
}
?>
<!doctype html>
<html>

<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Ingreso al Sitio</title>
</head>

<body class="container-fluid">
    <div class="contenedorLogin">
        <div class="imagenfondo">
            <img src="imagenes/cloud.svg" />
        </div>

        <div class="logueo">
            <div class="card">
                <form action="<?php echo $Accion_Formulario; ?>" method="post">
                    <div class="card-header">
                        <strong>Login</strong>
                    </div>
                    <div class="card-body">
                        <fieldset>
                            <label>Usuario:</label><input type="text" name="txtUsua" size="22" maxlength="15" required /><br>
                            <label>Contrase&ntilde;a:</label><input type="password" name="txtContra" size="22" maxlength="15" required />
                        </fieldset>
                    </div>
                    <div class="card-footer">
                        <input class="btn btn-outline-success btn-sm" type="submit" value="Aceptar" />
                        <a class="btn btn-outline-info btn-sm" href="registrar.php">Registrarse</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once('partes/final.inc'); ?>
</body>

</html>