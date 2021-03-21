<!DOCTYPE html>
<html>
	<head>
		<title>Alta invitados</title>
		<link rel="stylesheet" href="geo.css"/>
	</head>
	<body>
		<?php 
			if (!isset($_POST["provincia"]) || isset($_POST["volver"]))
				header("Location: " . dirname(htmlspecialchars($_SERVER["PHP_SELF"])) . "\\comunidades.php");
			
			$conn = new mysqli("localhost", "root", "", "geografia");
			if (!$conn)
			{
				echo "ERROR: No se pudo establecer la conexión.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}
			
			$result = $conn->query("SELECT nombre FROM localidades WHERE n_provincia = (SELECT n_provincia FROM provincias WHERE nombre = '" . $_POST["provincia"] . "')");
			if ($result->num_rows == 0)
			{										
				echo "ERROR: La base de datos no tiene datos.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}			
		?>
		<div>
			<form action="<?php echo htmlspecialchars("localidades.php"); ?>" method="post">
				<h2>Por último seleccione la localidad de <?php echo $_POST["provincia"]; ?> que desee:</h2>
				<br/>
					<select name="localidad" required>
						<?php
							while ($row = $result->fetch_assoc())
							{
								echo $row["nombre"] . " " . $_POST["localidad"];
							?>
								<option value="<?php echo $row["nombre"]; ?>" <?php echo (isset($_POST["localidad"]) && $row["nombre"] == $_POST["localidad"] ? "selected" : ""); ?>><?php echo $row["nombre"]; ?></option>
							<?php
							}
						?>
					</select>
				<input type="hidden" name="provincia" value="<?php echo $_POST["provincia"]; ?>" />
				<input type="submit" name="Mostrar" value="Continuar"/>
			</form>
		<?php
			if (isset($_POST["Mostrar"]))
			{
			?>		
				<h2>Datos de la localidad <?php echo $_POST["localidad"]; ?>:</h2>
				<table>
			<?php
				$SQL = "SELECT c.nombre AS c_nombre, p.id_capital, p.nombre AS p_nombre, l.poblacion, l.id_localidad
						FROM comunidades c, provincias p, localidades l
						WHERE c.id_comunidad = p.id_comunidad
						AND p.n_provincia = l.n_provincia
						AND l.nombre = '" . $_POST["localidad"] . "';";
				$result = $conn->query($SQL);
				while ($row = $result->fetch_assoc())
				{
				?>
					<tr>
						<th>Nombre de la localidad:</th><td><?php echo $_POST["localidad"]; ?></td>
					</tr>
					<tr>
						<th>Identificador:</th><td><?php echo $row["id_localidad"]; ?></td>
					</tr>
					<tr>
						<th>Población:</th><td><?php echo $row["poblacion"]; ?></td>
					</tr>
					<tr>
						<th>Capital de provincia?:</th><td><?php echo ($row["id_capital"] == $row["id_localidad"] ? "Sí" : "No"); ?></td>
					</tr>
					<tr>
						<th>Provincia a la que pertenece:</th><td><?php echo $row["p_nombre"]; ?></td>
					</tr>
					<tr>
						<th>Comunidad autónoma a la que pertenece:</th><td><?php echo $row["c_nombre"]; ?></td>
					</tr>
				<?php
				}
			?>		
				</table>
				<br/>
				<form action="<?php echo htmlspecialchars("comunidades.php"); ?>" method="post" enctype="multipart/form-data">
					<td colspan=2><input type="submit" value="<?php echo "Volver al inicio" ?>" name="volver" id="volver"/></td>
				</form>
			<?php
			}
		?>
		</div>
		<?php
			mysqli_close($conn);
		?>
  </body>
</html>