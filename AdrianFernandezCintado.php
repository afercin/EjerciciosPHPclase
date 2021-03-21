<!DOCTYPE html>
<html>
	<head>
		<title>Listado de números primos</title>
	</head>
	<body>
		<form action="AdrianFernandezCintado.php" method="post">
			<p>Introduzca cuantos números primos se van a mostrar por pantalla: <input type="number" name="numero" /></p>
			<p><input type="submit" name="submit" value="Consular" /></p>
		</form>
		<?php
			if (isset($_POST["numero"]))
			{
				$nprimos = $_POST["numero"];	 // Variable que contiene el número restante de números primos que debo de mostrar por pantalla.
				
				$numero = 1;					 // Variable que representa el número a calcular si es un número primo, empiezo en 1 ya que empezar en 0 no tiene sentido, ademas de que daría
												 // un error ya que no se puede dividir entre 0.
				
				echo "Listado de los primeros " . $nprimos . " números primos <br/>";
				
				while ($nprimos > 0) 			// El bucle se repite mientras no haya mostrado todos los números primos que me piden.
				{
					if (isPrime($numero))																		  // Si resulta que mi variable $numero es un número primo
						echo "El " . ($_POST["numero"] - --$nprimos) . "º número primo es: " . $numero . "<br/>"; // lo saco por pantalla y le realizo un predecremento a la variable $nprimos
																												  // ya que me queda un número menos por mostrar, el calculo que realizo es: 
																												  // TOTAL - (Restante - 1), si hiciera un postdecremento la variable $nprimos no disminuiría
																												  // en 1 antes de realizar esta operación.
																												  
					$numero++;					// Sea cual sea el resultado del if aumento en 1 mi número para volver a calcularlo en la siguiente iteración.
				}
			}
						  
			/* Por definición un número primo es todo aquel cuyos únicos múltiplos son 1 y el mismo número (el resto de su división da 0).
			 * Para resolver este problema he optado por ver si tiene algún otro múltiplo, descartando los dos que ya sabemos que va a tener (1 y $numero), 
			 * por lo que el rango de búsqueda sería [2, $numero).
			 */
			function isPrime($numero)
			{
				$var = 2; 						 // Variable utilizada en cada iteración del bucle para comprobar si es un múltiplo de $numero. 
				
				while ($var < $numero) 			 // Mientras $var sea menor a número cumpliendo de esta forma el invervalo cerrado.
				{					
					if ($numero % $var == 0)	 // Si el resto de la división de $numero partido de $var da cero quiere decir %var es múltiplo $numero,
						return false;			 // y por lo tanto $numero no puede ser un número primo y la función debe de devolver FALSE.
						
					$var++;						 // Siempre al terminar una iteración del bucle incremento en 1 el valor de $var para prepararlo en su siguiente comprobación
				}
				return true;					 // Si ha completado el bucle sin salir de la función quiere decir que no se ha encontrado ningún múltiplo, por lo que la 
			}									 // función debe de devolver TRUE
			
			
			/* También es posible implementar la función con un único return declarando una variable booleana para que salga del bucle en el momento que encuentre un múltimo.
			 *
			function isPrime($numero)
			{
				$prime = true;					 // Varibale booleana que me dice si el número es primo. Mientras no se demuestre lo contrario, todos los números son considerados primos.
				$var = 2;
				while ($var < $numero && $prime) // Repito mientras $var sea menor a $numero al igual que en la función anterior, pero añado la variable prime para que cuando deje de ser
				{								 // un número primo no siga calculando los números restantes hasta llegar a $numero - 1.
				
					if ($numero % $var == 0)     // Es exactamente el mismo if de la función anterior, solo que esta vez en lugar de hacer un return false al comprobarse que tiene un múltiplo,
						$prime = false;			 // se cambia el valor de $prime a false, con lo que en la siguiente iteración saldrá del bucle.
					$var++;
				}
				return $prime;					 // Y al final de la función devuelvo el valor de $prime, será TRUE si ha completado el bucle sin encontrar ningún múltimo, o FALSE en caso contrario
			}
			*
			*/
			
		?>
  </body>
</html>