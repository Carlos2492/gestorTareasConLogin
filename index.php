<?php
require("admin/conex_bd.php");
session_name("bd_tareas_login");
session_start();

if ($conexion = conectar()) {
    mysqli_set_charset($conexion, 'utf8');
// El flujo de ejecución iniciará consultando si existen las variables de sesión, en caso de ser Verdadero, comprobaremos el tiempo de sesión y daremos paso a la web principal
    if (isset($_SESSION['usuario']) && isset($_SESSION['clave']) && isset($_SESSION['sesion'])) {
//Comprobamos que el usuario y la contraseña en las variables de sesión son las que tenemos en la base de datos (seguridad)
        $consulta = "SELECT *  FROM usuarios WHERE nombre='" . $_SESSION['usuario'] . "' and password=md5('" . $_SESSION['clave'] . "')";

        if ($resultado = mysqli_query($conexion, $consulta)) {//En caso de obtener más de una fila , significa que existe
            if (mysqli_num_rows($resultado) > 0) {
                if ($datos_usu = mysqli_fetch_assoc($resultado)) {//Limpiamos caché ( por el select que acabamos de hacer)
                    mysqli_free_result($resultado);
                    //aquí comprobamos el tiempo transcurrido desde la ultima vez que el usuario realizó una acción
                    $tiempoActual = time();
                    $tiempoTranscurrido = $tiempoActual - $_SESSION['sesion'];
                    if ($tiempoTranscurrido > 60 * 5) {//En caso de que halla transucrrido, lo sacamos del sistema
                        $_SESSION['error'] = "tiempo";
                        unset($_SESSION['usuario']);
                        unset($_SESSION['clave']);
                        unset($_SESSION['sesion']);
                        header("Location: index.php");
                        exit;
                    } else {
                        $_SESSION['sesion'] = time();

                        //en caso de que todo esté correcto, actualizamos la variable de tiempo y damos paso a a la web
                        require("vistas/vista_normal.php");

                    }

                } else {
                    die("No se ha podido extraer los datos con éxito");
                }
            } else {
                $_SESSION['error'] = "restringido";
                mysqli_free_result($resultado);
                header("Location: index.php");
            }
        } else {
            $error = "Error de conexion nº " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
        }


    } else {    // en caso de que no existan las variables de sesión comprobamos si se ha pulsado el boton de registrar o logear
        if (isset($_POST['btnRegistrarse']) || isset($_POST['btnAceptarRegistro'])) {
            require("vistas/registro.php");
        } else {
            if (isset($_POST['btnEntrar'])) {
                require("admin/login_usuario.php");
            }


            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8"/>
                <link rel="stylesheet" href="assets/css/estilo.css"/>
                <title>Gestor de Tareas</title>
                <style>span {
                        color: red;
                    }</style>
            </head>
            <body>
            <header>
                <div class="form-main">
                    <h1>Gestor de Tareas</h1>
                    <?php

                    if (isset($_SESSION['error'])) {
                        if ($_SESSION['error'] == "restringido") {
                            echo "<span>¡Accediendo a una zona restringida!</span>";

                        }
                        if ($_SESSION['error'] == "malUsuario") {
                            echo "<span>¡Usuario no encontrado en la base de datos!</span>";

                        }
                        if ($_SESSION['error'] == "tiempo") {
                            echo "<span>¡Su sesión ha caducado!</span>";

                        }
                        if ($_SESSION['error'] == "comentario") {
                            echo "<span>¡Debes de estar registrado para poder dejar un comentario!</span>";

                        }
                        unset($_SESSION['error']);
                    }

                    ?>
                    <form action="index.php" method="POST">
                        <table>
                            <tr>
                                <div class="form-group">
                                    <td>
                                        <label>Nombre de Usuario:</label>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="user"/><br/>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($_SESSION['error2']) && $_SESSION['error2'] == "usuarioVacio") {
                                            echo "<span>*Campo Vacío*</span>";
                                            unset($_SESSION['error2']);
                                        }
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
                                        <input class="form-control" type="password" name="password"/><br/>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($_SESSION['error1']) && $_SESSION['error1'] == "claveVacia") {
                                            echo "<span>*Campo Vacío*</span>";
                                            unset($_SESSION['error1']);
                                        }
                                        ?>
                                    </td>
                                </div>
                            </tr>

                        </table>
                        <button class="btn btn-primary btn-block" type="submit" name="btnEntrar">Entrar</button>
                        <button class="btn btn-primary btn-block" type="submit" name="btnRegistrarse">Registrarse</button>
                    </form>
                </div>
            </header>


            </body>
            </html>
            <?php

        }
    }

} else {
    $error = "No se pudo conectar con la base de datos Error número: " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
    mysqli_close($conexion);
    die($error);
}

?>
