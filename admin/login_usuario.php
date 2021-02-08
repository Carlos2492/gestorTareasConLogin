<?php
       
            if(isset($_POST['btnEntrar']))
			{
                //LIMPIAMOS LOS INPUTS INTRODUCIDOS POR EL USUARIO
                $usuario=mysqli_real_escape_string($conexion,$_POST["user"]);
                $clave=mysqli_real_escape_string($conexion,$_POST["password"]);

                //CONTROLAMOS QUE ESCRIBA ALGO
                if($usuario=="" || $clave=="")
                {
                    if($usuario=="")
                    {
                        $_SESSION['error2']="usuarioVacio";
                    }
                    if($clave=="")
                    {
                        $_SESSION['error1']="claveVacia";
                    }
                    header("Location: index.php");
                    exit;
                }
                //CONSULTAMOS SI EL USUARIO EXISTE CON ESA PASS (ENCRIPTADA)
                $consulta="SELECT * FROM usuarios WHERE nombre='".$usuario."' AND password= md5('".$clave."')";

                if($resultado = mysqli_query($conexion,$consulta))  
                {//SI NUESTRA CONSULTA DEVUELVE MÁS DE 0 ROWS SIGNIFICA QUE EXISTE EN NUESTRA BBDD UN USUARIO CON ESE ID Y ESA PASSWORD
                    if(mysqli_num_rows($resultado) > 0)
                    {//Y CÓMO SIEMPRE EN NUESTRA WEB, DAREMOS PASO A LA FUNCIONALIDAD DE LA MISMA CREANDO VARIAS VARIABLES DE SESION
                        $_SESSION['usuario']=$usuario;
                        $_SESSION['clave']=$clave;
                        $_SESSION['sesion']=time();
                        header("Location: ./index.php");
                        exit;
                    }else
                    {// EN CASO DE QUE NO SEA ASÍ GENERAMOS UN ERROR DE USUARIO
                        mysqli_free_result($resultado);
                        $_SESSION['error']="malUsuario";
                        header("Location: index.php");
                        exit;
                    }

                }else
                {
                    die("Imposible conectar. Error número: ".mysqli_errno($conexion). ":".mysqli_error($conexion));  
                    header("Location: ./index.html");
                    exit;
                }

			}else{// AQUI CONTROLAMOS QUE NO SE ACCEDA A ESTA PARTE DE LA WEB SIN NUESTRO CONSENTIMIENTO
                session_name("bd_tareas_login");
                session_start();
                $_SESSION['error']="restringido";
                header("Location: ../index.php");
                exit;
            }
?>