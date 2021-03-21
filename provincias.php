<!DOCTYPE html>
<html>
	<head>
		<title>Alta invitados</title>
		<link rel="stylesheet" href="geo.css">
	</head>
	<body>
		<?php 
			if (!isset($_POST["comunidad"]))
				header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\comunidades.php");
				
			$conn = new mysqli("localhost", "root", "", "geografia");
			if (!$conn)
			{
				echo "ERROR: No se pudo establecer la conexión.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}
			
			$result = $conn->query("SELECT nombre FROM provincias WHERE id_comunidad = (SELECT id_comunidad FROM comunidades WHERE nombre = '" . $_POST["comunidad"] . "');");
			if ($result->num_rows == 0)
			{										
				echo "ERROR: La base de datos no tiene datos.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}
		?>
		<div>
			<form action="<?php echo htmlspecialchars("localidades.php"); ?>" method="post">
				<h2>Ahora seleccione la provincia de <?php echo $_POST["comunidad"]; ?> que desee consultar:</h2>
				<br/>
				<select name="provincia" required>
					<?php
						while ($row = $result->fetch_assoc())
						{
						?>
							<option value="<?php echo $row["nombre"]; ?>"><?php echo $row["nombre"]; ?></option>
						<?php
						}
					?>
				</select>
				<input type="submit" value="Continuar" name="submit"/>
			</form>
		</div>
		<?php
			mysqli_close($conn);
		?>
  </body>
</html>