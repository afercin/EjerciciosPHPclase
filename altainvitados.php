<!DOCTYPE html>
<html>
	<head>
		<title>Alta invitados</title>
	</head>
	<body>
		<div style="float:left">
			<table>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<tr><td>Nombre: </td><td><input type="text" name="name" /></td></tr>
					<tr><td>email: </td><td><input type="text" name="email" /></td></tr>
					<tr><td>Contraseña: </td><td><input type="password" name="password" /><td><tr>
					<tr><td colspan=2><input type="submit" name="Insertar" value="Insertar" style="width:100%"/></td></tr>
					<tr><td colspan=2><input type="submit" name="Mostrar" value="Mostrar todos" style="width:100%"/></td></tr>
				</form>
			</table>
		</div>
		<table>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<tr><td>Nombre: </td><td><input type="text" name="name" /></td></tr>
				<tr><td>Contraseña: </td><td><input type="password" name="password" /><td><tr>
				<tr><td colspan=2><input type="submit" name="Eliminar" value="Eliminar" style="width:100%"/></td></tr>
			</form>
		</table>
		<?php
			function comprobarValor($valor)
			{
				return empty($valor) ? "NULL" : "'" . $valor . "'";
			}
			
			function insertarValores($conn)
			{
				$SQL = "INSERT INTO myguest VALUES (
							NULL,"
							. comprobarValor($_POST["name"]) . "," 
							. comprobarValor($_POST["email"]) . ","
							. comprobarValor($_POST["password"]) . 
						")";
				if ($conn->query($SQL))
					echo "Usuario " . $_POST["name"] . " insertado con éxito";
				else
					echo "Error al introducir el usuario: " . $conn->error;
			}
			
			function mostrarValores($conn)
			{
				$SQL = "SELECT * 
						FROM myguest";
				$result = $conn->query($SQL);
				if ($result)
				{
				?>
					<table>
						<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Contraseña</th></tr>
				<?php
				
					while ($row = $result->fetch_assoc())
					{
						?>
						<tr>
							<td> <?php echo $row["id"]; ?></td>
							<td> <?php echo $row["nombre"]; ?></td>
							<td> <?php echo $row["email"]; ?></td>
							<td> <?php echo $row["password"]; ?></td>
						</tr>
						<?php
					}
				?>
					</table>
				<?php
				}
				else
					echo "No hay datos en la tabla myguest.";
			}
			
			function eliminarValores($conn)
			{
				$SQL = "SELECT id 
						FROM myguest
						WHERE nombre = " . comprobarValor($_POST["name"]) . " 
						AND password = " . comprobarValor($_POST["password"]);
				$result = $conn->query($SQL);
				if ($result->num_rows > 0)
				{
					$SQL = "DELETE FROM myguest WHERE id = " . $result->fetch_assoc()["id"];
					if ($conn->query($SQL))
						echo "Usuario " . $_POST["name"] . " eliminado con éxito";
					else
						echo "No se han encontrado usuarios.";
				}
				else
					echo "No hay ningun usuario con esas credenciales.";
			}
			
			$conn = new mysqli("localhost", "root", "", "prueba");
			
			if ($conn)
			{
				echo "Conexión establecida.<br/>";
				if (isset($_POST["Insertar"]))
					insertarValores($conn);
					
				if (isset($_POST["Mostrar"]))
					mostrarValores($conn);
				
				if (isset($_POST["Eliminar"]))
					eliminarValores($conn);
			}
			else
			{
				echo "No se pudo establecer la conexión.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}
			
			mysqli_close($conn);
		?>
  </body>
</html>