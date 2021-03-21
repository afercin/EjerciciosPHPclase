<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>TODO List</title>
		<link rel="stylesheet" href="lista.css">
	</head>
	<body>
	<?php
		/*
		 * Función que elimina una tarea del array $_SESSION, la eliminación de esta tarea se lleva a cabo reemplazando
		 * todas las tareas con su siguiente desde la que se desea eliminar y eliminando con unset() la última, ya que
		 * estará repetida, y finalmente se le resta 1 al contador de tareas.
		 * $task_ID es el identificador de la tarea que debe desaparecer.
		 */
		function Task_Delete($task_ID)
		{
			for ($_TASK = $task_ID; $_TASK < $_SESSION["NTASKS"]; $_TASK++)
				$_SESSION["Tarea " . $_TASK] = $_SESSION["Tarea " . ($_TASK + 1)];
			
			unset($_SESSION["Tarea " . $_SESSION["NTASKS"]--]);
		}
		/*
		 * Función que añade una tarea al array $_SESSION y a la misma vez preincrementa en 1 el contador de tareas.
		 * $task_info es la información de la tarea que acaba de introducir el usuario.
		 */
		function Task_Add($task_info)
		{
			$_SESSION["Tarea " . ++$_SESSION["NTASKS"]] = $task_info;
		}
		/*
		 * Función que devuelve el identificador de una tarea que se pasa por parametro, para extraer el identificador
		 * se separa el string por sus espacios y se selecciona el último valor ya que los string que procesa en mi
		 * código tienen la siguiente estructura: "Eliminar Tarea X".
		 * $task_name es el string donde está el idenficiador de la tarea.
		 */
		function GetTaskID($task_name)
		{
			$string_array = explode(" ", $task_name);
			return $string_array[count($string_array) - 1];
		}
		
		if (!isset($_SESSION["NTASKS"])) // Si la sesión se acaba de crear inicializo el contador de tareas a 0.
			$_SESSION["NTASKS"] = 0;
		
		if (isset($_POST["task_info"]) && !empty($_POST["task_info"])) // Si el usuario ha introducido una tarea la añado.
			Task_Add($_POST["task_info"]);
		
		if (isset($_POST["delete_task"]) && $_SESSION["NTASKS"] > 0) // Si el usuario ha pulsado el botón de eliminar se
			Task_Delete(GetTaskID($_POST["delete_task"]));			 // elimina la tarea asociada a este.
	?>
		<div>
			<table>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
					<tr>
						<td><h1>Lista de tareas</h1></td>
					</tr>
					<tr>
						<td>Nueva tarea a realizar:</td>
					</tr>
					<tr>
						<td><textarea name="task_info" cols="40" rows="5"></textarea></td>
					</tr>
					<tr>
						<td><input type="submit" id="inputbutton" value="Añadir Tarea" name="submit"/></td>
					</tr>
				</form>
			</table>
	<?php			
		if ($_SESSION["NTASKS"] > 0) // Si el usuario tiene tareas las muestro todas en una tabla.
		{
		?>
			<table>
				<tr>
					<td colspan=3><h2>Tareas restantes:</h2></td>
				</tr>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
		<?php
			foreach($_SESSION as $key => $value)
				if($key != "NTASKS")
				{
				?>
					<tr>
						<td><?php echo $key; ?>:</td>
						<td id="middle"><?php echo $value; ?></td>
						<td><input type="submit" name="delete_task" value="Eliminar <?php echo $key; ?>"/> </td>
					</tr>
				<?php
				}
		?>
				</form>
			</table>
		<?php
		}
		else // Si no tiene tareas muestro un texto informativo y una imagen.
		{
		?>
			<br/>
			<p>No tiene ninguna tarea por realizar.</p>
			<img src="https://www.incimages.com/uploaded_files/image/1920x1080/getty_459097117_89221.jpg"/>
		<?php
		}
	?>
		</div>
	</body>
</html>