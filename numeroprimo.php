<!DOCTYPE html>
<html>
	<head>
		<title>Conversor de monedas</title>
	</head>
	<body>
		<form action="numeroprimo.php" method="post">
			<p>Número a calcular el factorial: <input type="number" name="numero" /></p>
			<p><input type="submit" name="submit" value="Calcular" /></p>
		</form>
		<?php
			if (isset($_POST["numero"]))
				echo "El número " . $_POST["numero"] . (esPrimo($_POST["numero"]) ? " " : " no ") . "es un número primo";
			
			function esPrimo($numero)
			{
				$var = 2;
				while ($var < $numero)
				{					
					if ($numero % $var == 0)
						return false;
					$var++;
				}
				return true;
			}
		?>
  </body>
</html>