<!DOCTYPE html>
<html>
  <head>
    <title>Conversor de monedas</title>
  </head>
  <body>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	  Cantidad: <input type="text" name="valor" /><br/>
	  Moneda inicial:
	  <select id="monedaInicial" name="monedaInicial">
        <option value="Euros">Euros</option>
        <option value="Dolares">Dolares</option>
        <option value="Libras">Libras</option>
        <option value="Yenes">Yenes</option>
      </select>
	  Moneda final:
	  <select id="monedaFinal" name="monedaFinal">
        <option value="Euros">Euros</option>
        <option value="Dolares">Dolares</option>
        <option value="Libras">Libras</option>
        <option value="Yenes">Yenes</option>
      </select>
	  <p><input type="submit" name="submit" value="Convertir" /></p>
	</form>
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			$conversion = [
			"Euros" => 1.00,
			"Dolares" => 1.17,
			"Libras" => 0.91,
			"Yenes" => 123.75,
			];
			$valor = ($_POST["valor"] / $conversion[$_POST["monedaInicial"]]) * $conversion[$_POST["monedaFinal"]];
			echo $_POST['valor'] . " " .$_POST['monedaInicial'] . " equivalen a " . $valor . " " . $_POST['monedaFinal'];
		}
	?>
  </body>
</html>