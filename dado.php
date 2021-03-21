<!DOCTYPE html>
<html>
	<head>
		<title>Tirar dado</title>
	</head>
	<body>
		<form action="dado.php" method="post">
			<p>Veces que se tira el dado: <input type="number" name="count" /></p>
			<p><input type="submit" name="submit" value="Calcular" /></p>
		</form>
		<?php
			if (isset($_POST["count"]))
			{
				$contadores = array(0,0,0,0,0,0);
				for($i = 0; $i < $_POST["count"]; $i++)
					$contadores[rand(1,6) - 1]++;
				for($i = 0; $i < count($contadores); $i++)
					echo "El " . ($i + 1) . " ha salido " . $contadores[$i] . " ve" . ($contadores[$i] == 1 ? "z" : "ces") . "<br/>";
			}
		?>
  </body>
</html>