<!DOCTYPE html>
<html>
	<head>
		<title>Adrián Fernández Cintado</title>
		<style>
		body
		{
			background-image: url('https://i.pinimg.com/originals/7b/96/9e/7b969ee5dbca31dff3037df9b6ab501c.jpg');
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-size: cover;
			background-position: center;
			font-family: "Arial";
			font-size: 20px;
		}
		div
		{
			border-radius: 5px;
			min-width: 320px;
			min-height: 120px;
			margin-left: 22%;
			margin-top: 100px;
			margin-right: 22%;
			padding: 30px;
			background-color: rgba(253,221,167,0.8);
			text-align: center;
		}
		h2 span
		{
			font-size: 13px;
		}
		.provincia
		{
			font-style:italic;
			color: rgb(20,100,150);
		}
		.aciertos
		{
			color: #777;
		}
		</style>
	</head>
	<body>
		<?php
			function LocalidadAleatoria($conn)
			{
				$result = $conn->query("SELECT nombre, n_provincia FROM localidades");
				$result->data_seek(mt_rand(1, $result->num_rows));
				$localidad = $result->fetch_assoc();
				return array($localidad["nombre"], $localidad["n_provincia"]);
			}
		
			$conn = new mysqli("localhost", "root", "", "geografia");
			if (!$conn)
			{
				echo "ERROR: No se pudo establecer la conexión.<br/>";
				die( print_r( sqlsrv_errors(), true));
			}	
			
			$aciertos = isset($_POST["aciertos"]) ? $_POST["aciertos"] : 0;
			$intentos = isset($_POST["intentos"]) ? $_POST["intentos"] : 0;
			
			if (isset($_POST["check"]))
			{
				$intentos++;
				if ($_POST["provincia"] == $_POST["provincia_correcta"])
					$aciertos++;
			}
			
			$localidad = LocalidadAleatoria($conn);
		?>
		<div>
			<h2>¡Adiviname! <span class="aciertos">(versión de provincias)</span></h2>
			<h3>
				¿A qué provincia pertenece la localidad con el nombre <span class="provincia">"<?php echo $localidad[0]; ?>"</span>? 
				<?php 
					if ($intentos > 0) 
						echo "<br/><span class='aciertos'>Aciertos: " .$aciertos . "/" . $intentos . "</span>"; 
				?>
			</h3>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<select name="provincia" required>
					<?php
						$result = $conn->query("SELECT nombre, n_provincia FROM provincias");
						while ($row = $result->fetch_assoc())
						{
						?>
							<option value="<?php echo $row["n_provincia"]; ?>"><?php echo $row["nombre"]; ?></option>
						<?php
						}
					?>
				</select>
				<input type="submit" name="check" value="¡Comprobar!"/>
				<input type="hidden" name="provincia_correcta" value="<?php echo $localidad[1]; ?>"/>
				<input type="hidden" name="aciertos" value="<?php echo $aciertos; ?>"/>
				<input type="hidden" name="intentos" value="<?php echo $intentos; ?>"/>
			</form>
		</div>
  </body>
</html>