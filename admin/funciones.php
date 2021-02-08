<?php
	
	function repetido_n($columna,$valor,$conex)
			{
				$rep=false;
				$consulta="select nombre from usuarios where ".$columna."='".$valor."'";
				if($resultado=mysqli_query($conex,$consulta))
				{
					if(mysqli_num_rows($resultado)>0)
					{
						$rep=true;
						mysqli_free_result($resultado);
					}
				}
				else
				{
					$error="Imposible realizar la consulta. Error número: ".mysqli_errno($conex). ":".mysqli_error($conex);
					mysqli_close($conex);
					die($error);	
				}

				return $rep;

			}

function delete($id_tarea)
{
    if ($conexion = conectar()) {

        $consulta_eliminar = "DELETE FROM tareas WHERE id_tarea='" . $id_tarea . "'";

        if (mysqli_query($conexion, $consulta_eliminar)) {
            return true;
        } else {
            die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
            mysqli_close($conexion);
        }
    } else {
        $error = "No se pudo conectar con la base de datos Error número: " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
        mysqli_close($conexion);
        die($error);
    }
}
function add($tarea, $categorias,$usuario)
{
    if ($conexion = conectar()) {
		//CONSEGUIMOS EL ID DE USUARIO A PARTIR DE SU NOMBRE
		$consulta_id_usuario="SELECT id_usuario FROM usuarios where nombre='$usuario'";
		if ($resultado_id_usuario = mysqli_query($conexion, $consulta_id_usuario)) {
			if ($fila = mysqli_fetch_array($resultado_id_usuario)) {
				$id_usuario=$fila["id_usuario"];
				// INSERTAMOS LA TAREA NUEVA
				$consulta = "INSERT INTO tareas (nombre_tarea,id_usuario) VALUES ('$tarea','$id_usuario')";
		
				if (mysqli_query($conexion, $consulta)) {
					//SACAMOS EL ID DE LA TAREA A TRAVÉS DEL NOMBRE
					$consulta_compleja = "SELECT id_tarea FROM tareas where nombre_tarea='$tarea'";
					if ($resultado_consulta2 = mysqli_query($conexion, $consulta_compleja)) {
						if ($fila = mysqli_fetch_array($resultado_consulta2)) {
							if (count($categorias) > 0) {
						  
								$nuevaTarea = $fila['id_tarea'];
								// DEPENDIENDO DEL NÚMERO DE CATEGORIAS ITERAMOS INTRODUCIENDOLOS
								for ($i = 0; $i < count($categorias); $i++) {
									$nuevaCategoria = "";
									switch ($categorias[$i]) {
										case 'PHP':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='PHP'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
										case 'Javascript':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='JavaScript'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
										case 'CSS':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='CSS'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
									}
		
									$consulta_iterativa = "INSERT INTO tareas_categorias (id_categoria,id_tarea) VALUES ('$nuevaCategoria','$nuevaTarea')";
									mysqli_query($conexion, $consulta_iterativa);
								}
							}
						}
					} else {
						die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
						mysqli_close($conexion);
					}
				} else {
					die("Error en la consulta a la base de datos Error111:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
					mysqli_close($conexion);
				}
			} else {
				$error = "No se pudo conectar con la base de datos Error número: " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
				mysqli_close($conexion);
				die($error);
			}
			}
		}
	
}

function tarea_repetida($columna,$valor)
{
	if ($conexion = conectar())
	{
		$rep=false;
		$consulta="select nombre_tarea from tareas where ".$columna."='".$valor."'";
		if($resultado=mysqli_query($conexion,$consulta))
		{
			if(mysqli_num_rows($resultado)>0)
			{
				$rep=true;
				mysqli_free_result($resultado);
			}
			
			
		}
		else
		{
			$error="Imposible realizar la consulta. Error número: ".mysqli_errno($conexion). ":".mysqli_error($conexion);
			mysqli_close($conexion);
			die($error);	
		}

		return $rep;
	}
	else 
	{
        $error = "No se pudo conectar con la base de datos Error número: " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
        mysqli_close($conexion);
        die($error);
    }

}

function modificar_tarea($tarea, $categorias,$usuario)
{
    if ($conexion = conectar()) {
		$consulta_id_usuario="SELECT id_usuario FROM usuarios where nombre='$usuario'";
		if ($resultado_id_usuario = mysqli_query($conexion, $consulta_id_usuario)) {
			if ($fila = mysqli_fetch_array($resultado_id_usuario)) {
				$id_usuario=$fila["id_usuario"];
				//igual que la función de añadir  pero modificamos en vez de introducir
				$consulta = "UPDATE  tareas SET nombre_tarea='$tarea' , id_usuario='$id_usuario'";
		
				if (mysqli_query($conexion, $consulta)) {
					$consulta_compleja = "SELECT id_tarea FROM tareas where nombre_tarea='$tarea'";
					if ($resultado_consulta2 = mysqli_query($conexion, $consulta_compleja)) {
						if ($fila = mysqli_fetch_array($resultado_consulta2)) {
							if (count($categorias) > 0) {
						  
								$nuevaTarea = $fila['id_tarea'];

								for ($i = 0; $i < count($categorias); $i++) {

									$consulta_delete="DELETE from tareas_categorias where id_tarea='$nuevaTarea'";
									mysqli_query($conexion,$consulta_delete);
								}
								for ($i = 0; $i < count($categorias); $i++) {
									$nuevaCategoria = "";
									switch ($categorias[$i]) {
										case 'PHP':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='PHP'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
										case 'Javascript':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='JavaScript'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
										case 'CSS':
											$consulta_categoria = "SELECT id_categoria FROM categorias WHERE nombre_categoria='CSS'";
											if ($resultado_consulta_categoria = mysqli_query($conexion, $consulta_categoria)) {
												if ($fila_categoria = mysqli_fetch_array($resultado_consulta_categoria)) {
													$nuevaCategoria = $fila_categoria["id_categoria"];
												}
											}
											break;
									}
		
									$consulta_iterativa = "INSERT INTO tareas_categorias (id_categoria,id_tarea) VALUES ('$nuevaCategoria','$nuevaTarea')";
									mysqli_query($conexion, $consulta_iterativa);
								}
							}
						}
					} else {
						die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
						mysqli_close($conexion);
					}
				} else {
					die("Error en la consulta a la base de datos Error111:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
					mysqli_close($conexion);
				}
			} else {
				$error = "No se pudo conectar con la base de datos Error número: " . mysqli_errno($conexion) . " : " . mysqli_error($conexion);
				mysqli_close($conexion);
				die($error);
			}
			}
		}
	
}


?>
