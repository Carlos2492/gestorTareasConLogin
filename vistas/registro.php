<?php


$error_user = true;
$error_pass = true;
$error_name = true;
$error_apell = true;
$error_mail = true;
if (isset($_POST['btnvolver'])) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['btnAceptarRegistro'])) {
    require("admin/funciones.php");
    //LIMPIEZA DE INYECCIÓN SQL
    $usuario = mysqli_real_escape_string($conexion, $_POST["usuario"]);
    $pass = mysqli_real_escape_string($conexion, $_POST["contrasenia"]);
    $descripcion = mysqli_real_escape_string($conexion, $_POST["descripcion"]);
    $direccion = mysqli_real_escape_string($conexion, $_POST["direccion"]);
    $mail = mysqli_real_escape_string($conexion, $_POST["mail"]);
    $cp = mysqli_real_escape_string($conexion, $_POST["cp"]);
    //CONTROL DE CAMPOS VACIOS Y USUARIO REPETIDO
    $error_user = ($usuario == "" || repetido_n("nombre", $usuario, $conexion));
    $error_pass = $pass == "";
    $error_descripcion = $descripcion == "";
    $error_direccion = $direccion == "";
    $error_mail = ($mail == "" || repetido_n("mail", $mail, $conexion));
    $error_cp = $cp == "";
    // SI PASA EL CONTROL DE ERRORES, REGISTRAMOS
    $error = (!$error_user && !$error_pass && !$error_descripcion && !$error_direccion && !$error_mail && !$error_cp);

    if ($error) {
        //en la misma consulta aplico la funcion md5() y encripto la pass, aunque MD5  sea un método de encriptación en desuso
        $consulta = "INSERT INTO usuarios (nombre,password,descripcion,direccion,mail,codigo_postal) VALUES ('" . $usuario . "','" . md5($pass) . "','" . $descripcion . "','" . $direccion . "','" . $mail . "','$cp')";

        if ($resultado = mysqli_query($conexion, $consulta)) {
            // EN CASO DE QUE SE REALICE LA CONSULTA CON ÉXITO, CREAREMOS LAS VARIABLES DE SESION QUE NOS VALDRÁN PARA ACCEDER A LA FUNCIONALIDAD DE LA WEB
            $_SESSION['usuario'] = $usuario;
            $_SESSION['clave'] = $pass;
            $_SESSION['sesion'] = time();//ESTE SERVIRÁ PARA CONTROLAR EL TIEMPO DE SESIÓN
            header("Location: ./index.php?5");

        } else {
            echo $consulta;
            $error = "Error al realizar la consulta";
            mysqli_close($conexion);
            die($error);

        }
    }
}

if (isset($_POST['btnRegistrarse']) || isset($_POST['btnAceptarRegistro'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="assets/css/estilo.css"/>
        <title>Gestor Tareas</title>
    </head>
    <body>
    <div class="form-main">
        <h1>Registro de Usuario</h1>
        <form action="index.php" method="POST">
            <table>
                <tr>
                    <div class="form-group">
                        <td>
                            <label>Nombre:</label>
                        </td>
                        <td>
                            <input class="form-control" type="text" name="usuario"
                                   value="<?php if (isset($_POST['btnAceptarRegistro']) && isset($_POST['usuario'])) echo $_POST['usuario'] ?>"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['usuario'] == "") echo "<span>*Campo Vacío*</span>";
                            if (isset($_POST['btnAceptarRegistro']) && repetido_n("nombre", $_POST['usuario'], $conexion)) echo "<span>*Usuario ya en uso*</span>";
                            ?>
                        </td>
                    </div>
                </tr>
                <tr>
                    <div class="form-group">
                        <td>
                            <label for="">Contraseña:</label>
                        </td>
                        <td>
                            <input class="form-control" type="password" name="contrasenia"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['contrasenia'] == "") echo "<span>*Campo Vacío*</span>";
                            ?>
                        </td>
                    </div>
                </tr>
                <tr>
                    <div class="form-group">
                        <td>
                            <label>Descripcion:</label>
                        </td>
                        <td>
                            <input class="form-control" type="text" name="descripcion"
                                   value="<?php if (isset($_POST['btnAceptarRegistro']) && isset($_POST['descripcion'])) echo $_POST['descripcion'] ?>"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['descripcion'] == "") echo "<span>*Campo Vacío*</span>";
                            ?>
                        </td>
                    </div>
                </tr>
                <tr>
                    <div class="form-group">
                        <td>
                            <label>Direccion:</label>
                        </td>
                        <td>
                            <input class="form-control" type="text" name="direccion"
                                   value="<?php if (isset($_POST['btnAceptarRegistro']) && isset($_POST['direccion'])) echo $_POST['direccion'] ?>"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['direccion'] == "") echo "<span>*Campo Vacío*</span>";
                            ?>
                        </td>
                    </div>
                </tr>
                <tr>
                    <div class="form-group">
                        <td>
                            <label>Código Postal:</label>
                        </td>
                        <td>
                            <input class="form-control" type="text" name="cp"
                                   value="<?php if (isset($_POST['btnAceptarRegistro']) && isset($_POST['cp'])) echo $_POST['cp'] ?>"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['cp'] == "") echo "<span>*Campo Vacío*</span>";
                            ?>
                        </td>
                    </div>
                </tr>
                <tr>
                    <div class="form-group">
                        <td>
                            <label>Correo-Electrónico:</label>
                        </td>
                        <td>
                            <input class="form-control" type="text" name="mail"
                                   value="<?php if (isset($_POST['btnAceptarRegistro']) && isset($_POST['mail'])) echo $_POST['mail'] ?>"/><br/>
                        </td>
                        <td>
                            <?php
                            if (isset($_POST['btnAceptarRegistro']) && $_POST['mail'] == "") echo "<span>*Campo Vacío*</span>";
                            if (isset($_POST['btnAceptarRegistro']) && repetido_n("mail", $_POST['mail'], $conexion)) echo "<span>*Mail ya en uso*</span>";
                            ?>
                        </td>
                    </div>
                </tr>

            </table>
            <button class="btn btn-primary btn-block" type="submit" name="btnAceptarRegistro">Registrarse</button>
            <button class="btn btn-primary btn-block" type="submit" name="btnvolver">Volver</button>

        </form>
    </div>

    </body>
    </html>
    <?php
} else {
    session_name("bd_tareas_login");
    session_start();
    $_SESSION['error'] = "restringido";
    header("Location: ../index.php");
    exit;
}
?>
