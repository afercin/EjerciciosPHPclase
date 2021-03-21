<!DOCTYPE html>
<html>
	<head>
		<title>Conversor de monedas</title>
	</head>
	<body>
		<form action="factorial.php" method="post">
			<p>Número a calcular el factorial: <input type="number" name="numero" /></p>
			<p><input type="submit" name="submit" value="Calcular" /></p>
		</form>
		<?php
			if (isset($_POST["numero"]))
			{
				$numero = $_POST["numero"];
				$valor = 1;
				while($numero > 1)
					$valor *= $numero--;
				echo "El factorial de " . $_POST["numero"] . " es igual a " . $valor . ".";
			}
		?>
  </body>
</html>