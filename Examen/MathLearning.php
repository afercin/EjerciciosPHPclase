<!---
	1º ¿Qué significa que http sea un protocolo sin estado? Indica las formas que conoces para poder resolver este inconveniente.
	
	Que http sea un protocolo sin estado significa que todo lo que hagamos en este protocolo (páginas web) va a permanecer constante y no va a ser posible que cambie su información
	a menos de que cambiemos a otra página.
	Para el protocolo http se utiliza el lenguaje de marcas llamado html, el cual se puede complementar utilizando:
		- JavaScript para acciones que tengan que llevarse a cabo en el lado del cliente. Por ejemplo la acción que desencadena el pulsar un botón.
		- PHP para realizar acciones que se tengan que realizar en el lado del servidor. Por ejemplo iniciar sesión o extraer información de las cookies.

-->
<!DOCTYPE html>
<html>
	<head>
		<title>Adrián Fernández Cintado</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="MathLearning.css">
	</head>
	<body>
		<div>
			<?php
				/*  Función que devuelve el valor en decimal de un número situado dentro de un string.
				 *	$str: es el string que contiene el número.
				 *	$offset: desplazamiento dentro del string (por si el número a extraer no está al principio de este).
				 */
				function ParseString($str, $offset)
				{
					$strArray = str_split($str); // Array que contiene todos los caracteres de mi string.
					$formatedStr = "";			 // String que contendrá el número extraido del array.
					$position = $offset;		 // Posición dentro del array.
					
					// Se repite mientras no se llegue al final del array y no se llegue a un espacio en blanco.
					while ($position < strlen($str) && $strArray[$position] != " ")
						$formatedStr = $formatedStr . $strArray[$position++]; // Concateno el caracter actual a mi string de salida y postincremento la variable de posición.
					
					return floatval($formatedStr); // Devuelvo el número en coma flotante utilizando la función floatval().
				}
				
				/*  Función que comprueba si la respuesta introducida por el usuario es correcta.
				 *	No recibe argumentos.
				 */
				function IsCorrectResult()
				{
					$isCorrect = false; // Mientras no se demuestre lo contrario mi usuario se ha equivocado en el resultado.
					
					// Si el usuario no ha escrito una respuesta no tengo porqué comprobar si es una respuesta correcta.
					if (isset($_POST["result"]) && strlen($_POST["result"]) > 0)
					{
						$operand1 = ParseString($_SESSION["operand1"], 0);	// Extraigo y transformo el string con el primer operando de la operación anterior.
						$operand2 = ParseString($_SESSION["operand2"], 2); // Extraigo y transformo el string con el segundo operando de la operación anterior. 
																		// Pongo offset = 2 ya que este string contiene a su vez el tipo de operación
						$result; // Variable que almacena el resultado correcto de la operación matemática.
						
						switch (str_split($_SESSION["operand2"])[0]) // El primer carácter del segundo operando es el tipo de operación, por ello lo extraigo y realizo la operación correspondiente.
						{
							case "+": $result = $operand1 + $operand2; break;
							case "-": $result = $operand1 - $operand2; break;
							case "x": $result = $operand1 * $operand2; break;
							case "/": $result = $operand1 / $operand2; break;
						}
						// Redondeo a dos decimales para no tener en cuenta un posible fallo en el redondeo del tercer decimal.
						$isCorrect = round($result, 2) == round(ParseString($_POST["result"], 0), 2); // Compruebo si el resultado que ha introducido el usuario y el correcto valen lo mismo. Almaceno el valor booleano en $isCorrect.
						
						/* -----Versión anterior-------
						// Redondeo el resultado a 3 decimales ya que es el máximo número de decimales le permito al usuario poner en su respuesta.
						$isCorrect = abs(round($result, 3) - ParseString($_POST["result"], 0)) <= 0.01; // Compruebo si el resultado que ha introducido el usuario y el correcto valen lo mismo (su resta debe de ser 0) 
																										// pero permito que el usuario cometa un error de 0,01 en el redondeo de los decimales.
						*/
					}
					
					return $isCorrect; // Devuelvo un valor de tipo bool que dice si es finalmente correcta la respuesta.
				}
				
				$name = isset($_POST["name"]) && strlen($_POST["name"]) > 0 ? $_POST["name"] : "Anonymous"; // Extraigo el nombre de mi usuario, si no ha introducido ninguno pasa a llamarse Anonymous.
				$age = isset($_POST["age"]) && strlen($_POST["age"]) > 0 ? $_POST["age"] : 12;				// Extraigo la edad de mi usuario, si no ha introducido ninguna pasa a tener 12 años.
			?>
			
			<!-- Saco por pantalla el nombre y la edad de mi usuario en un mensaje de bienvenida. -->
			<h2>Wellcome <?php echo $name; ?> to MathLearning!</h2>
			<h3>Math operations for users bellow <?php echo $age; ?> years</h3>
			
			<?php
				session_start();
				$report = isset($_SESSION["report"]) ? $_SESSION["report"] : ""; // Extraigo un string que contiene cada operación realizada con la respuesta del usuario y si están contestadas correctamente, 
																		   // en la primera ejecución se inicializa como una cadena vacía.
				$questionCount; // Variable que contendrá el número de operaciones que ha realizado mi usuario.
				
				if (isset($_SESSION["count"])) // Si no es la primera ejecución, el enunciado que dice el número de la operación actual tendrá un valor.
				{
					$questionCount = $_SESSION["count"] + 1;
					// Concateno en la operación que se realizó en la ejecución anterior con el resultado que introdujo el usuario y lo separo por ";" de si es correcto el resultado introducido.
					$report = $report . $_SESSION["operand1"] . " " . $_SESSION["operand2"] . " = " 
									  . (isset($_POST["result"]) && strlen($_POST["result"]) > 0 ? $_POST["result"] : "NaN") . ";"  // Si no ha escrito un valor en lugar de dejarlo en blanco escribo que no es un número (NaN).
									  . (IsCorrectResult() ? "Correct!" : "Wrong!") . ";"; // Concateno si el resultado es correcto o no y pongo ";" al final para separarlo del resultado de la siguiente ejecución.
				}
				else
					$questionCount = 0; // Si es la primera ejecución inicializo a 0 el contador.
				
				if ($questionCount < 5) // Si el usuario no ha realizado 10 operaciones le preparo otra.
				{
					$operatorSymbol = array("+", "-", "x", "/"); // Array que contiene los símbolos de las diferentes operaciones matemáticas disponibles.
					$operator = $operatorSymbol[rand(0, 3)]; 	 // Extraigo un operador de forma aleatoria. 
					$operand1 = rand(0, 99);	// Genero dos operandos de 2 cifras como máximo de forma aleatoria
					$operand2 = rand(0, 99);
					
					while ($operator == '/' && $operand2 == 0) // Si es una división y el divisor es 0 debo de buscar otro valor ya que no es posible dividir entre cero.
						$operand2 = rand(0, 99);
					
					$_SESSION["count"] = $questionCount;
					$_SESSION["operand1"] = $operand1;
					$_SESSION["operand2"] = $operator . " " . $operand2; 
					$_SESSION["report"] = $report;
			?>
			<!--  Mantengo el formulario por el CSS. -->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
				<table>
					<tr> <!-- Texto con el número de la operación a realizar -->
						<td> <input type="text" name="Text" class="inputLabel" value="Math operations <?php echo ($questionCount + 1); ?> of 5:" readonly/> </td> 
					</tr>
					<tr> <!-- Texto con el primer operando. -->
						<td> <input type="text" name="operand1" class="inputLabel operationLabel" value="<?php echo $operand1; ?>" readonly/> </td> 
					</tr>
					<tr> <!-- Texto con el tipo de operación y el segundo operando. -->
						<td> <input type="text" name="operand2" class="inputLabel operationLabel operand2" value="<?php echo $operator . " " . $operand2; ?>" readonly/> </td> 
					</tr>
					<tr> <!-- Cuadro de texto que contendrá la respuesta ofrecida por el usuario. -->
						<td> Your answer: <input type="number" name="result" class="operationLabel" step=".001" max="9801" autofocus/> </td> 
					</tr>
					<tr> 
						<td> <input type="submit" name="submit" class="SubmitButton" value="Send"/> </td> 
					</tr>
				</table> 
			</form>
			
			<?php
				}
				else // Si mi usuario ya ha realizado 5 operaciones le muestro una tabla con los resultados.
				{
					$reportArray = explode(";", $report); // Tal y como se vió arriba, este string separa cada elemento por ";", por ello utilizo explode() para extraer cada uno de los valores en un array.
			?>
			
			<table class="ReportTable">
			<?php
					// Salgo del bucle cuando queda un elemento restante ya que al terminar mi string $report en ";" explode() me genera un último valor vacío que debo descartar.
					for($i = 0; $i < count($reportArray) - 1; $i+=2) // Recorro el array de 2 en 2 ya que operación y corrección ocupan dos lugares en el array.
						echo "<tr>" . 
								 "<th>" . ($i / 2 + 1) . ") " . "</th>" . // Para dar más información también digo el número de la operación actual.
								 "<td>" . $reportArray[$i] . "</td>" .
								 "<td>" . $reportArray[$i + 1] . "</td>" .
							 "</tr>";
			?>
				<!-- Botón que destruye el formulario y recarga la página. -->
				<tr> 					
					<td colspan=3> <button onclick="Try_Again()" style="width: 100%;">Try again!</button></td> 
				</tr>
			</table>
			<script>
				function Try_Again()
				{
					<?php session_destroy(); ?>
					window.location.reload();
				}
			</script>
			<?php		
				}
			?>
		</div>
	</body>
</html>