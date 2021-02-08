<?php
require("./admin/funciones.php");

//Modificado de Tareas
if(isset($_POST["btnMod"]))
{
	$array_categorias = array();

	if (isset($_POST["PHP"]))
		$array_categorias[count($array_categorias)] = "PHP";

	if (isset($_POST["Javascript"]))
		$array_categorias[count($array_categorias)] = "Javascript";

	if (isset($_POST["CSS"]))
		$array_categorias[count($array_categorias)] = "CSS";

	if (!$_POST["nuevo_nombre"] == "") {
		if (!tarea_repetida("nombre_tarea", $_POST["nuevo_nombre"])) {
	
			modificar_tarea($_POST["nuevo_nombre"], $array_categorias, $_SESSION["usuario"]);
		}
	}
	
}
// BORRADO DE TAREAS
if (isset($_POST["btnBorrar"])) {
	delete($_POST["btnBorrar"]);
}
// INTRODUCCIÓN DE TAREAS
if (isset($_POST["btnAdd"])) {
	$array_categorias = array();

	if (isset($_POST["PHP"]))
		$array_categorias[count($array_categorias)] = "PHP";

	if (isset($_POST["Javascript"]))
		$array_categorias[count($array_categorias)] = "Javascript";

	if (isset($_POST["CSS"]))
		$array_categorias[count($array_categorias)] = "CSS";

	if (isset($_POST["nombre"])) {
		if (!$_POST["nombre"] == "") {
			if (!tarea_repetida("nombre_tarea", $_POST["nombre"])) {
				add($_POST["nombre"], $array_categorias, $_SESSION["usuario"]);
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="assets/css/estilo.css" rel="stylesheet" />
	<title>Ejercicio 2</title>
</head>

<body>
	<div id='gestorTareas'>
		<h1>Gestor de tareas</h1>
		<hr>
		Bienvenido <b><?php echo $_SESSION["usuario"]; ?></b> <a href="./admin/cerrar_sesion.php">Cerrar sesión</a>
		<form action="index.php" method="POST">
			<div id="crearTarea">

				<div class="create-container">
					<input class="name-input" type="text" name="nombre" placeholder="Nueva tarea...">
				</div>

				<div class="checkbox-container">

					<label for="PHP">
						PHP
						<input type="checkbox" name="PHP" value="PHP">
					</label>
					<label for="Javascript">
						Javascript
						<input type="checkbox" name="Javascript" value="Javascript">
					</label>
					<label for="CSS">
						CSS
						<input type="checkbox" name="CSS" value="CSS">
					</label>
				</div>
				<div class="input-container">
					<button class="btn-add" type="submit" name="btnAdd" value="Añadir">Añadir</button>
				</div>
		</form>
	</div>
	<table class="table-class">
		<thead>
			<tr>
				<th>Tarea</th>
				<th>Categorias</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<?php

		$consulta_tc = "SELECT * FROM tareas WHERE id_usuario = '".$datos_usu['id_usuario']."'";

        if ($resultado_tc = mysqli_query($conexion, $consulta_tc)) {

			echo "<tbody>";
			echo "<tr>";
			while ($fila_tc = mysqli_fetch_assoc($resultado_tc)) {
				$id_eliminar = $fila_tc['id_tarea'];
				$consulta_t = "SELECT * FROM tareas_categorias WHERE id_tarea='" . $fila_tc['id_tarea'] . "'";

				if ($resultado_t = mysqli_query($conexion, $consulta_t)) {

					echo "<td>";
					echo $fila_tc["nombre_tarea"];
					echo "</td>";
					echo "<td>";
					while ($fila_t = mysqli_fetch_assoc($resultado_t)) {



						$consulta_c = "SELECT * FROM categorias WHERE id_categoria='" . $fila_t['id_categoria'] . "'";

						if ($resultado_c = mysqli_query($conexion, $consulta_c)) {
							while ($fila_c = mysqli_fetch_assoc($resultado_c)) {
								echo "<button class='categoriaBtn' value='" . $fila_c["nombre_categoria"] . "'>" . $fila_c["nombre_categoria"] . "</button>";
							}
						} else {
							die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
							mysqli_close($conexion);
						}
					}
					echo "</td>";
					echo "<td class='td-borrar'>
                       <form action='index.php' method='POST'> <button class='btn-add'  type='submit' name='btnModificar' value='$id_eliminar'>Modificar</button><button class='btn-add'  type='submit' name='btnBorrar' value='$id_eliminar'>X</button></form>
                     </td>";
					echo "</tr>";
				} else {
					die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
					mysqli_close($conexion);
				}
			}
			echo "</tr>";
			echo "</tbody>";
		} else {
			die("Error en la consulta a la base de datos Error:" . mysqli_errno($conexion) . ":" . mysqli_error($conexion));
			mysqli_close($conexion);
		}
		?>
	</table>
    <?php
	//MODIFICAR TAREAS
if (isset($_POST["btnModificar"])) {
	?>
	<h1>Modificar Tarea</h1>
		<form action="index.php" method="POST">
			<div id="crearTarea">
	
				<div class="create-container">
					<input class="name-input" type="text" name="nuevo_nombre" placeholder="Nuevo nombre...">
				</div>
	
				<div class="checkbox-container">
	
					<label for="PHP">
						PHP
						<input type="checkbox" name="PHP" value="PHP">
					</label>
					<label for="Javascript">
						Javascript
						<input type="checkbox" name="Javascript" value="Javascript">
					</label>
					<label for="CSS">
						CSS
						<input type="checkbox" name="CSS" value="CSS">
					</label>
				</div>
				<div class="input-container">
					<button class="btn-add" type="submit" name="btnMod" value="Modificar">Modificar</button>
				</div>
		</form>
		</div>
	<?php
	}
	?>
</body>

</html>
